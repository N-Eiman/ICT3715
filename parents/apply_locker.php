<?php
session_start();
include('../include/header.php');
include('../include/db_connect.php');

require_once '../PHPMailer-master/src/PHPMailer.php';
require_once '../PHPMailer-master/src/SMTP.php';
require_once '../PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ensure parent is logged in
if (!isset($_SESSION['parentID'])) {
    header("Location: parent_login.php");
    exit;
}

$studentID = $_GET['student'] ?? null;
$parentID  = $_SESSION['parentID'];

// Get student
$studentQuery = $conn->query("SELECT * FROM student WHERE studentSchoolNumber = '$studentID' AND parentID = '$parentID'");
$student = $studentQuery->fetch_assoc();

if (!$student) {
    echo "Student not found or not linked to you.";
    exit;
}

$grade = $student['studentGrade'];

// Check if already applied
$alreadyBooked = $conn->query("SELECT * FROM bookings WHERE studentSchoolNumber = '$studentID'")->num_rows;
$alreadyWaiting = $conn->query("SELECT * FROM waiting_list WHERE studentSchoolNumber = '$studentID'")->num_rows;

if ($alreadyBooked || $alreadyWaiting) {
    echo "<script>alert('Student already has an existing booking or is on the waiting list.'); window.location.href = 'parent_dashboard.php';</script>";
    exit;
}

// --- Apply grade-based limits (demo values)
$gradeLimit = ($grade == 'Grade 8') ? 10 : (($grade == 'Grade 12') ? 5 : 9999);

// Count booked lockers in same grade
$bookedCount = $conn->query("
    SELECT COUNT(*) AS total
    FROM bookings b
    JOIN student s ON b.studentSchoolNumber = s.studentSchoolNumber
    WHERE s.studentGrade = '$grade'
")->fetch_assoc()['total'];

// Insert into correct table
if ($bookedCount < $gradeLimit) {
    // Generate random booked_for date between 2026-07-01 and 2026-10-30
    $start = strtotime("2026-07-01");
    $end   = strtotime("2026-10-30");
    $randomDate = date("Y-m-d", rand($start, $end));

    // Insert into bookings including studentGrade
    $conn->query("
        INSERT INTO bookings (parentID, studentSchoolNumber, lockersID, booked_for, studentGrade)
        VALUES ('$parentID', '$studentID', NULL, '$randomDate', '$grade')
    ");
    $status = "Booked - Awaiting Allocation";
    $message = "Locker application received for {$student['studentName']}. Awaiting admin allocation.";

    // Also Add to waiting list
  $now = date("Y-m-d H:i:s");

    $conn->query("
        INSERT INTO waiting_list (studentSchoolNumber, parentID, requestedGrade, appliedDate, status)
        VALUES ('$studentID', '$parentID', '$grade', '$now', 'Awaiting Allocation')
    ");

    $status = "Waitlisted";
    $message = "Admin to allocate lockers for {$student['studentName']}, for now {$student['studentGrade']} has been placed on the waiting list.";
}

// --- Email sending ---
$parentDetails = $conn->query("SELECT * FROM parents WHERE parentID = '$parentID'")->fetch_assoc();
$parentName  = $parentDetails['parentName'] . ' ' . $parentDetails['parentSurname'];
$parentEmail = 'nitaeims@gmail.com'; // For testing, use a fixed emai

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'nitaeims@gmail.com';  // your Gmail
    $mail->Password   = 'czqi drbc reft rzww';       // app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('nitaeims@gmail.com', 'Centurion High School Locker System');
    $mail->addReplyTo('nitaeims@gmail.com', 'Centurion High School');

    // Send to parent
    $mail->addAddress($parentEmail, $parentName);
    // Send to admin
    $mail->addCC('tjeiman@outlook.com', 'Admin Officer');

    $mail->isHTML(true);
    $mail->Subject = "Locker Application Result for {$student['studentName']}";
    $mail->Body = "
        <p><strong>Locker Application Details:</strong></p>
        <ul>
            <li><strong>Parent Name:</strong> $parentName</li>
            <li><strong>Parent Email:</strong> $parentEmail</li>
            <li><strong>Student Name:</strong> {$student['studentName']} {$student['studentSurname']}</li>
            <li><strong>Student Grade:</strong> {$student['studentGrade']}</li>
            <li><strong>Status:</strong> $status</li>
        </ul>
        <p>$message</p>
    ";
    $mail->send();

    echo "<script>alert('Application successful. Confirmation email sent to $parentEmail and admin notified.'); window.location.href = 'parent_dashboard.php';</script>";
} catch (Exception $e) {
    echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

include('../include/footer.php');
?>
