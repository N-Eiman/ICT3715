<?php  
session_start();
if (!isset($_SESSION['adminID'])) {
    header("Location: admin_login.php");
    exit;
}

include '../include/db_connect.php';
include '../include/header.php';

require_once '../PHPMailer-master/src/PHPMailer.php';
require_once '../PHPMailer-master/src/SMTP.php';
require_once '../PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = '';

// Function to generate random date between two given dates for the booked_for field
function randomDate($startDate, $endDate) {
    $min = strtotime($startDate);
    $max = strtotime($endDate);
    $val = mt_rand($min, $max);
    return date('Y-m-d', $val);
}

// --- Handle locker allocation ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'allocate') {
    $studentSchoolNumber = (int) $_POST['studentSchoolNumber'];
    $lockersID = $conn->real_escape_string(trim($_POST['lockersID']));
    $adminRecordID = (int) $_SESSION['adminID'];
    $randomBookedFor = randomDate("2026-01-01", "2026-12-01");

    if (empty($studentSchoolNumber) || empty($lockersID)) {
        $message = "Please select a student and a locker.";
    } else {
        $conn->begin_transaction();
        try {
            // Check locker availability
            $check = $conn->prepare("SELECT grade, assigned FROM lockers WHERE lockersID = ?");
            $check->bind_param("s", $lockersID);
            $check->execute();
            $check->bind_result($lockerGradeInt, $lockerAssigned);
            if (!$check->fetch()) throw new Exception("Locker not found.");
            $check->close();
            if ($lockerAssigned === 'Yes') throw new Exception("Selected locker is no longer available.");

            // Check if student is in bookings
            $stmt = $conn->prepare("SELECT lockersID FROM bookings WHERE studentSchoolNumber = ? LIMIT 1");
            $stmt->bind_param("i", $studentSchoolNumber);
            $stmt->execute();
            $stmt->bind_result($existingLocker);
            $isInBookings = $stmt->fetch() ? true : false;
            $stmt->close(); 

           if ($isInBookings) {
            if (!is_null($existingLocker) && $existingLocker !== '') {
                throw new Exception("Student already has a locker ($existingLocker).");
            }

            // Get parentID from bookings table
            $parentIDToUse = $conn->query("SELECT parentID, studentName, studentSurname, studentGrade FROM bookings WHERE studentSchoolNumber = $studentSchoolNumber LIMIT 1")->fetch_assoc();
            if (!$parentIDToUse) throw new Exception("Booking record not found for email.");
            $studentName = $parentIDToUse['studentName'];
            $studentSurname = $parentIDToUse['studentSurname'];
            $gradeToUse = $parentIDToUse['studentGrade'];
            $parentIDToUse = $parentIDToUse['parentID'];

            // Update booking
            $upd = $conn->prepare("UPDATE bookings 
                                SET lockersID = ?, recordID = ?, booking_date = NOW(), booked_for = ? 
                                WHERE studentSchoolNumber = ?");
            $upd->bind_param("sisi", $lockersID, $adminRecordID, $randomBookedFor, $studentSchoolNumber);
            if (!$upd->execute()) throw new Exception("Failed to update booking: " . $conn->error);
            $upd->close();
            
            } else {
                // Check waiting list
                $wl = $conn->prepare("SELECT studentName, studentSurname, parentID, requestedGrade 
                                      FROM waiting_list WHERE studentSchoolNumber = ? LIMIT 1");
                $wl->bind_param("i", $studentSchoolNumber);
                $wl->execute();
                $wl->bind_result($wlName, $wlSurname, $wlParentID, $wlRequestedGrade);
                $inWaiting = $wl->fetch() ? true : false;
                $wl->close();

                if ($inWaiting) {
                    $studentName = $wlName;
                    $studentSurname = $wlSurname;
                    $parentIDToUse = $wlParentID;
                    $gradeToUse = $wlRequestedGrade;
                } else {
                    $st = $conn->prepare("SELECT studentName, studentSurname, studentGrade, parentID 
                                          FROM student WHERE studentSchoolNumber = ? LIMIT 1");
                    $st->bind_param("i", $studentSchoolNumber);
                    $st->execute();
                    $st->bind_result($studentName, $studentSurname, $gradeToUse, $parentIDToUse);
                    if (!$st->fetch()) throw new Exception("Student record not found.");
                    $st->close();
                }

                // Insert into bookings
                $ins = $conn->prepare("
                    INSERT INTO bookings
                        (parentID, studentSchoolNumber, studentName, studentSurname, booking_date, booked_for, recordID, lockersID, studentGrade)
                    VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?)
                ");
                $ins->bind_param("iisssiss", $parentIDToUse, $studentSchoolNumber, $studentName, $studentSurname, $randomBookedFor, $adminRecordID, $lockersID, $gradeToUse);
                if (!$ins->execute()) throw new Exception("Failed to insert booking: " . $ins->error);
                $ins->close();

                if ($inWaiting) {
                    $del = $conn->prepare("DELETE FROM waiting_list WHERE studentSchoolNumber = ?");
                    $del->bind_param("i", $studentSchoolNumber);
                    $del->execute();
                    $del->close();
                }
            }

            // Mark locker as assigned
            $mark = $conn->prepare("UPDATE lockers SET assigned = 'Yes' WHERE lockersID = ?");
            $mark->bind_param("s", $lockersID);
            $mark->execute();
            $mark->close();

            $conn->commit();
            $message = "Locker $lockersID successfully assigned to student $studentSchoolNumber (booked_for: $randomBookedFor).";

            // Send email to parent
            $parentDetails = $conn->query("SELECT * FROM parents WHERE parentID = '$parentIDToUse'")->fetch_assoc();
            if ($parentDetails) {
                $parentName  = $parentDetails['parentName'] . ' ' . $parentDetails['parentSurname'];
                $parentEmail = $parentDetails['parentEmail'];
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'nitaeims@gmail.com';
                    $mail->Password   = 'czqi drbc reft rzww';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    $mail->setFrom('nitaeims@gmail.com', 'Centurion High School Locker System');
                    $mail->addAddress($parentEmail, $parentName);
                    $mail->isHTML(true);
                    $mail->Subject = "Locker Allocation Notice for {$studentName} {$studentSurname}";
                    $mail->Body = "
                        <p>Dear $parentName,</p>
                        <p>Your child, <strong>{$studentName} {$studentSurname}</strong> in <strong>{$gradeToUse}</strong>, 
                        has been allocated <strong>Locker $lockersID</strong>.</p>
                        <p>Please note that a payment of <strong>R100</strong> is payable within 2 business days. 
                        If no payment is made, the locker may be suspended.</p>
                        <p>Booking Date: <strong>$randomBookedFor</strong></p>
                        <p>Thank you,<br>Centurion High School</p>
                    ";
                    $mail->send();
                } catch (Exception $e) {
                    $message .= " (Email not sent: {$mail->ErrorInfo})";
                }
            }

        } catch (Exception $e) {
            $conn->rollback();
            $message = "Allocation failed: " . $e->getMessage();
        }
    }
}

// --- Handle suspension ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['suspendStudent'], $_POST['suspendLocker'])) {
    $studentID = (int) $_POST['suspendStudent'];
    $lockerID  = $conn->real_escape_string($_POST['suspendLocker']);

    $conn->begin_transaction();
    try {
        // Fetch booking details
        $bk = $conn->query("SELECT studentName, studentSurname, studentGrade, parentID FROM bookings WHERE studentSchoolNumber = $studentID")->fetch_assoc();
        if (!$bk) throw new Exception("Booking not found for student $studentID");

        // Insert back into waiting_list
        $stmt = $conn->prepare("INSERT INTO waiting_list (studentSchoolNumber, studentName, studentSurname, requestedGrade, parentID, appliedDate) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("isssi", $studentID, $bk['studentName'], $bk['studentSurname'], $bk['studentGrade'], $bk['parentID']);
        $stmt->execute();
        $stmt->close();

        // Delete from bookings
        $conn->query("DELETE FROM bookings WHERE studentSchoolNumber = $studentID");

        // Mark locker as unassigned
        $conn->query("UPDATE lockers SET assigned = 'No' WHERE lockersID = '$lockerID'");

        $conn->commit();
        $message = "Student $studentID suspended and moved back to waiting list. Locker $lockerID is now available.";

        // Send email to parent
        $parentDetails = $conn->query("SELECT * FROM parents WHERE parentID = {$bk['parentID']}")->fetch_assoc();
        if ($parentDetails) {
            $parentName  = $parentDetails['parentName'] . ' ' . $parentDetails['parentSurname'];
            $parentEmail = $parentDetails['parentEmail'];

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'nitaeims@gmail.com';
                $mail->Password   = 'czqi drbc reft rzww';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('nitaeims@gmail.com', 'Centurion High School Locker System');
                $mail->addAddress($parentEmail, $parentName);
                $mail->isHTML(true);
                $mail->Subject = "Locker Suspension Notice for {$bk['studentName']} {$bk['studentSurname']}";
                $mail->Body = "
                    <p>Dear $parentName,</p>
                    <p>Your child, <strong>{$bk['studentName']} {$bk['studentSurname']}</strong> in <strong>{$bk['studentGrade']}</strong>, 
                    has been <strong>suspended from their locker ($lockerID)</strong> due to non-payment.</p>
                    <p>The student has been moved back to the waiting list and the locker is now available for reassignment.</p>
                    <p>Thank you,<br>Centurion High School</p>
                ";
                $mail->send();
            } catch (Exception $e) {
                $message .= " (Suspension email not sent: {$mail->ErrorInfo})";
            }
        }

    } catch (Exception $e) {
        $conn->rollback();
        $message = "Suspension failed: " . $e->getMessage();
    }
}

// --- Fetch students eligible for assignments ---
$students_sql = "
    SELECT DISTINCT s.studentSchoolNumber,
           CONCAT(s.studentSurname, ', ', s.studentName) AS fullName,
           s.studentGrade
    FROM student s
    LEFT JOIN bookings b ON s.studentSchoolNumber = b.studentSchoolNumber
    LEFT JOIN waiting_list w ON s.studentSchoolNumber = w.studentSchoolNumber
    WHERE (b.studentSchoolNumber IS NOT NULL AND b.lockersID IS NULL)
       OR (b.studentSchoolNumber IS NULL AND w.studentSchoolNumber IS NULL)
    ORDER BY s.studentSurname, s.studentName
";
$res = $conn->query($students_sql);
$students = [];
while ($r = $res->fetch_assoc()) $students[] = $r;
$res->free();

// --- Fetch available lockers ---
$lockers_res = $conn->query("SELECT lockersID, grade, location FROM lockers WHERE assigned = 'No' ORDER BY grade, lockersID");
$lockers = [];
while ($l = $lockers_res->fetch_assoc()) $lockers[] = $l;
$lockers_res->free();

// --- Fetch students with lockers for suspension ---
$suspendedRes = $conn->query("SELECT studentSchoolNumber, CONCAT(studentSurname, ', ', studentName) AS fullName, studentGrade, lockersID FROM bookings WHERE lockersID IS NOT NULL ORDER BY studentSurname, studentName");
$suspendedStudents = [];
while ($r = $suspendedRes->fetch_assoc()) $suspendedStudents[] = $r;
$suspendedRes->free();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Locker Application | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://kit.fontawesome.com/a2f2a1d6a2.js" crossorigin="anonymous"></script>
<style>
body { font-family: monospace, sans-serif; background:#bbe4e9; color:#5585b5; margin:0; padding:0; }
.container-main { max-width:1000px; margin:30px auto; }
.card { background: rgba(255,255,255,0.85); border-radius:12px; padding:20px; box-shadow:0 4px 20px rgba(0,0,0,0.08); }
.btn-primary { background:#5585b5; border:none; }
.btn-primary:hover { background:#446f91; }
.btn-danger { background:#d9534f; border:none; }
.btn-danger:hover { background:#c12e2a; }
.alert { font-weight:600; }
.small-note { color:#777; font-size:0.9rem; }
.lockers-list { max-height: 300px; overflow:auto; border:1px solid #eee; padding:10px; border-radius:8px; background:#fff; }
.students-scroll { max-height: 300px; overflow-y:auto; border:1px solid #eee; padding:10px; border-radius:8px; background:#fff; }
</style>
</head>
<body>

<main class="container-main">
  <div class="card">
    <h2><i class="fas fa-user-check"></i> Locker Application / Assign Locker</h2>
    <p class="small-note">Select a student without a locker, choose an available locker, then assign.</p>

    <?php if (!empty($message)): ?>
      <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- Assign Locker Form -->
    <form method="POST">
      <input type="hidden" name="action" value="allocate" />
      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label">Student (eligible)</label>
          <select class="form-select" id="studentSelect" name="studentSchoolNumber" required>
            <option value="">-- Select Student --</option>
            <?php foreach ($students as $s):
                $gradeNum = (int) filter_var($s['studentGrade'], FILTER_SANITIZE_NUMBER_INT); ?>
                <option value="<?= (int)$s['studentSchoolNumber'] ?>" data-grade="<?= $gradeNum ?>">
                    <?= htmlspecialchars($s['fullName']) ?> (<?= (int)$s['studentSchoolNumber'] ?>) — <?= htmlspecialchars($s['studentGrade']) ?>
                </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Locker (available)</label>
          <select class="form-select" id="lockerSelect" name="lockersID" required>
            <option value="">-- Select Locker --</option>
            <?php foreach ($lockers as $l): ?>
                <option value="<?= htmlspecialchars($l['lockersID']) ?>" data-grade="<?= (int)$l['grade'] ?>">
                    <?= htmlspecialchars($l['lockersID']) ?> — Grade <?= (int)$l['grade'] ?> — <?= htmlspecialchars($l['location']) ?>
                </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-12">
          <button type="submit" class="btn btn-primary"><i class="fas fa-check-circle"></i> Assign Locker</button>
        </div>
      </div>
    </form>

    <hr>

    <p class="small-note text-danger mt-3">Select a student with a locker to suspend due to non-payment.</p>

    <!-- Suspend Locker Form -->
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label">Student (Booked)</label>
          <select class="form-select" name="suspendStudent" required>
            <option value="">-- Select Student --</option>
            <?php foreach ($suspendedStudents as $s): ?>
                <option value="<?= (int)$s['studentSchoolNumber'] ?>" data-locker="<?= htmlspecialchars($s['lockersID']) ?>">
                    <?= htmlspecialchars($s['fullName']) ?> — <?= htmlspecialchars($s['studentGrade']) ?> (Locker: <?= htmlspecialchars($s['lockersID']) ?>)
                </option>
            <?php endforeach; ?>
          </select>
          <input type="hidden" name="suspendLocker" id="suspendLockerInput" />
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-12">
          <button type="submit" class="btn btn-danger"><i class="fas fa-minus-square"></i> Suspend Locker</button>
        </div>
      </div>
    </form>

    <hr>

    <div class="row">
      <div class="col-md-7">
        <h5>Students currently selectable for assignments</h5>
        <div class="students-scroll">
            <ul>
            <?php if (count($students) === 0): ?>
                <li>No eligible students found.</li>
            <?php else: ?>
                <?php foreach ($students as $s): ?>
                    <li><?= htmlspecialchars($s['fullName']) ?> (<?= (int)$s['studentSchoolNumber']?>) — <?= htmlspecialchars($s['studentGrade']) ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
            </ul>
        </div>
      </div>

      <div class="col-md-5">
        <h5>Available lockers</h5>
        <div class="lockers-list">
            <?php if (count($lockers) === 0): ?>
                <p>No available lockers.</p>
            <?php else: ?>
                <ul>
                <?php foreach ($lockers as $l): ?>
                    <li><?= htmlspecialchars($l['lockersID']) ?> — Grade <?= (int)$l['grade'] ?> — <?= htmlspecialchars($l['location']) ?></li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
      </div>
    </div>

  </div>
</main>

<script>
// Client-side locker filtering by selected student's grade
const studentSelect = document.getElementById('studentSelect');
const lockerSelect = document.getElementById('lockerSelect');

function filterLockers() {
    const studentOpt = studentSelect.options[studentSelect.selectedIndex];
    const targetGrade = studentOpt ? studentOpt.getAttribute('data-grade') : null;

    for (let i = 0; i < lockerSelect.options.length; i++) {
        const opt = lockerSelect.options[i];
        if (!opt.value) continue;
        const lg = opt.getAttribute('data-grade');
        opt.hidden = targetGrade && lg !== targetGrade;
        opt.disabled = targetGrade && lg !== targetGrade;
    }

    const sel = lockerSelect.options[lockerSelect.selectedIndex];
    if (sel && sel.getAttribute('data-grade') !== targetGrade) {
        lockerSelect.value = '';
    }
}

studentSelect.addEventListener('change', filterLockers);
document.addEventListener('DOMContentLoaded', filterLockers);

// Auto-fill hidden locker input for suspension
const suspendSelect = document.querySelector('select[name="suspendStudent"]');
const suspendLockerInput = document.getElementById('suspendLockerInput');

suspendSelect.addEventListener('change', () => {
    const opt = suspendSelect.options[suspendSelect.selectedIndex];
    suspendLockerInput.value = opt ? opt.getAttribute('data-locker') : '';
});
</script>

<?php include '../include/footer.php'; ?>
