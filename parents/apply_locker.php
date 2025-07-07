<?php
session_start();
include('../include/header.php');
include('../include/db_connect.php');


require_once '../PHPMailer-master/src/PHPMailer.php';
require_once '../PHPMailer-master/src/SMTP.php';
require_once '../PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if parent is logged in
if (!isset($_SESSION['parentID'])) {
    header("Location: parent_login.php");
    exit;
}

$studentID = $_GET['student'] ?? null;
$parentID = $_SESSION['parentID'];

// Get student details
$studentQuery = $conn->query("SELECT * FROM student WHERE studentSchoolNumber = '$studentID' AND parentID = '$parentID'");
$student = $studentQuery->fetch_assoc();

if (!$student) {
    echo "Student not found or not linked to you.";
    exit;
}

$grade = $student['studentGrade'];

// Check current booking
$alreadyBooked = $conn->query("SELECT * FROM bookings WHERE recordID = '$studentID'")->num_rows;
$alreadyWaiting = $conn->query("SELECT * FROM waitinglist WHERE recordID IN 
    (SELECT recordID FROM adminofficer WHERE studentSchoolNumber = '$studentID')")->num_rows;

if ($alreadyBooked || $alreadyWaiting) {
    echo "<script>alert('Student already has an existing booking or is on the waiting list.'); window.location.href = 'parent_dashboard.php';</script>";
    exit;
}


// Count booked lockers
$gradeLimit = ($grade == 'Grade 8') ? 10 : (($grade == 'Grade 12') ? 5 : 9999);
$bookedCount = $conn->query("
    SELECT COUNT(*) AS total
    FROM student s
    JOIN bookings b ON s.studentSchoolNumber = b.recordID
    WHERE s.studentGrade = '$grade'
")->fetch_assoc()['total'];

// Admin assignment (for demo, alternate between two admins)
$adminID = ($studentID % 2 == 0) ? 12345 : 56890;
$adminEmail = ($adminID == 12345) ? 'tjeiman@outlook.com' : 'patience.igor@gmail.com';
$adminName = ($adminID == 12345) ? 'Juanita Mayday' : 'Patience Igor';

// Add to adminofficer table
$conn->query("
    INSERT INTO adminofficer (adminID, studentSchoolNumber, adminName, adminEmail, password)
    VALUES ('$adminID', '$studentID', '$adminName', '$adminEmail', 'passwDemo')
");
$recordID = $conn->insert_id;

if ($bookedCount < $gradeLimit) {
    // Add to bookings
    $conn->query("
        INSERT INTO bookings (parentID, lockersID, recordID)
        VALUES ('$parentID', NULL, '$studentID')
    ");
    $status = "Booked";
    $message = "Locker successfully booked for your child, {$student['studentName']}.";
} else {
    // Add to waiting list
    $conn->query("
        INSERT INTO waitinglist (parentID, payment, lockersID, recordID)
        VALUES ('$parentID', 0.00, NULL, '$recordID')
    ");
    $status = "Waiting List";
    $message = "No locker currently available. Your child, {$student['studentName']}, has been placed on the waiting list.";
}

// Send email (simulate with PHPMailer)
$parentDetails = $conn->query("SELECT * FROM parents WHERE parentID = '$parentID'")->fetch_assoc();
$parentName = $parentDetails['parentName'] . ' ' . $parentDetails['parentSurname'];
$parentEmail = $parentDetails['parentEmail'];

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Use your SMTP settings
    $mail->SMTPAuth = true;
    $mail->Username = 'thakanenyabela@gmail.com';  // myemail address
    $mail->Password = 'qlid ehbo iepv fuww';         // the password I received from my email provider after creating an app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('thakanenyabela@gmail.com', 'Centurion High School Locker System');
    $mail->addAddress('thakanenyabela@gmail.com'); // Replace with my email address (thakanenyabela@gmail.com) for testing purposes

    $mail->isHTML(true);
    $mail->Subject = "Locker Application Result for {$student['studentName']}";
    $mail->Body = "<p><strong>New Application Details:</strong></p>
        <ul>
            <li><strong>Parent Name:</strong> $parentName</li>
            <li><strong>Parent Email (sample):</strong> $parentEmail</li>
            <li><strong>Student Name:</strong> {$student['studentName']} {$student['studentSurname']}</li>
            <li><strong>Student Grade:</strong> {$student['studentGrade']}</li>
            <li><strong>Status:</strong> $status</li>
        </ul>
        <p>This message was sent to your inbox to confirm that your application was received and the locker has been successfully booked, please send proof of payment in order to finalize your application.</p>";
    $mail->send();
    echo "<script>alert('Application successful. Confirmation email sent to $parentEmail'); window.location.href = 'parent_dashboard.php';</script>";
} catch (Exception $e) {
    echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}



include('../include/footer.php');
?>
