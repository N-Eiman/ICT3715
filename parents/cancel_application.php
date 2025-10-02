<?php
session_start();
include('../include/header.php');
include('../include/db_connect.php');

require_once '../PHPMailer-master/src/PHPMailer.php';
require_once '../PHPMailer-master/src/SMTP.php';
require_once '../PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['parentID'])) {
    header("Location: parent_login.php");
    exit;
}

$parentID   = $_SESSION['parentID'];
$parentName = $_SESSION['parentName'] ?? "Parent";

// Handle cancellation request
if (isset($_GET['cancel']) && !empty($_GET['cancel'])) {
    $studentID = intval($_GET['cancel']);

    // Remove booking if exists
    $deleted = false;
    if ($conn->query("DELETE FROM bookings WHERE studentSchoolNumber = '$studentID' AND parentID = '$parentID'")) {
        if ($conn->affected_rows > 0) {
            $deleted = true;
        }
    }

    // If not in bookings, remove from waiting_list
    if (!$deleted) {
        $conn->query("DELETE FROM waiting_list WHERE studentSchoolNumber = '$studentID' AND parentID = '$parentID'");
        if ($conn->affected_rows > 0) {
            $deleted = true;
        }
    }

    if ($deleted) {
        // Get student details
        $student = $conn->query("SELECT * FROM student WHERE studentSchoolNumber = '$studentID'")->fetch_assoc();
        $studentName = $student['studentName'] . " " . $student['studentSurname'];
        $grade       = $student['studentGrade'];

        // --- Send email to Admin ---
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'nitaeims@gmail.com'; // your Gmail
            $mail->Password   = 'czqi drbc reft rzww';       // your app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('nitaeims@gmail.com', 'Centurion High School Locker System');
            $mail->addAddress('tjeiman@outlook.com', 'Admin Officer');

            $mail->isHTML(true);
            $mail->Subject = "Locker Application Cancelled by Parent";
            $mail->Body    = "
                <p><strong>Parent:</strong> $parentName (ID: $parentID)</p>
                <p><strong>Cancelled Application:</strong></p>
                <ul>
                    <li><strong>Student:</strong> $studentName</li>
                    <li><strong>Grade:</strong> $grade</li>
                    <li><strong>Student No:</strong> $studentID</li>
                </ul>
                <p>Please update your records accordingly.</p>
            ";
            $mail->send();
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>Cancellation done, but email failed: {$mail->ErrorInfo}</div>";
        }

        echo "<script>alert('Application for $studentName has been cancelled. Admin notified.'); window.location.href='cancel_application.php';</script>";
        exit;
    } else {
        echo "<div class='alert alert-warning'>No active application found to cancel.</div>";
    }
}

// Fetch all students for this parent
$students = $conn->query("SELECT * FROM student WHERE parentID = '$parentID'");
?>

<body style="font-family: monospace, sans-serif;
  background: #bbe4e9;">
<div class="container py-4">
    <h3 class="text-center fw-bold mb-4">Cancel Applications — <?= htmlspecialchars($parentName) ?></h3>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Student</th>
                <th>Grade</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $students->fetch_assoc()): ?>
            <?php
            $studentID = $row['studentSchoolNumber'];
            $fullName  = $row['studentName'] . ' ' . $row['studentSurname'];
            $grade     = $row['studentGrade'];

            $statusLabel = "<span class='badge bg-secondary'>Not Applied</span>";
            $actionHTML  = "";

            // Check bookings
            $bookingRow = $conn->query("SELECT * FROM bookings WHERE studentSchoolNumber = '$studentID' AND parentID = '$parentID'")->fetch_assoc();
            if ($bookingRow) {
                if ($bookingRow['lockersID']) {
                    $statusLabel = "<span class='badge bg-success'>Allocated (Locker {$bookingRow['lockersID']})</span>";
                } else {
                    $statusLabel = "<span class='badge bg-info text-dark'>Booked - Awaiting Allocation</span>";
                }
                $actionHTML = "<a href='cancel_application.php?cancel=$studentID' class='btn btn-sm btn-danger'>Cancel</a>";
            }
            // Check waiting_list
            elseif ($conn->query("SELECT * FROM waiting_list WHERE studentSchoolNumber = '$studentID' AND parentID = '$parentID'")->num_rows > 0) {
                $statusLabel = "<span class='badge bg-warning text-dark'>Waitlisted</span>";
                $actionHTML  = "<a href='cancel_application.php?cancel=$studentID' class='btn btn-sm btn-danger'>Cancel</a>";
            }
            ?>
            <tr>
                <td><?= htmlspecialchars($fullName) ?></td>
                <td><?= htmlspecialchars($grade) ?></td>
                <td><?= $statusLabel ?></td>
                <td><?= $actionHTML ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <div class="text-end">
        <a href="parent_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

<?php include('../include/footer.php'); ?>
