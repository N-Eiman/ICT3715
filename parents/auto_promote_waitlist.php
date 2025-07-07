<?php
use PHPMailer\PHPMailer\PHPMailer; // ✅ Put this first
use PHPMailer\PHPMailer\Exception;

require_once '../PHPMailer-master/src/PHPMailer.php';
require_once '../PHPMailer-master/src/SMTP.php';
require_once '../PHPMailer-master/src/Exception.php';

include('../include/header.php');
include('../include/db_connect.php');

// Set grade limits
$gradeLimits = ['Grade 8' => 10, 'Grade 12' => 5];

$promotedCount = 0; // Initialize counter for promoted students

foreach ($gradeLimits as $grade => $limit) {
    // Count current bookings
    $sql = "
    SELECT COUNT(*) AS total
    FROM student s
    JOIN bookings b ON s.studentSchoolNumber = b.recordID
    WHERE s.studentGrade = '$grade'
    ";
    $result = $conn->query($sql);

    if (!$result) {
        die("Query failed: " . $conn->error); // This will tell us the exact error
    }

    $booked = $result->fetch_assoc()['total'];

    if ($booked >= $limit) continue;

    // Get first waitlisted student for this grade
    $result = $conn->query("
        SELECT w.*, s.studentSchoolNumber, s.studentGrade, s.parentID, s.studentName, s.studentSurname
        FROM waitinglist w
        JOIN adminofficer a ON w.recordID = a.recordID
        JOIN student s ON a.studentSchoolNumber = s.studentSchoolNumber
        WHERE s.studentGrade = '$grade'
        ORDER BY w.listID ASC LIMIT 1
    ");

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        $studentID = $student['studentSchoolNumber'];
        $recordID = $student['recordID'];
        $parentID = $student['parentID'];
        $promotedCount++; // Increment the counter for each promoted student   

        // Move to bookings
        $conn->query("INSERT INTO bookings (parentID, lockersID, recordID) VALUES ('$parentID', NULL, '$studentID')");
        $conn->query("DELETE FROM waitinglist WHERE listID = {$student['listID']}");

        // Get parent details (for logging)
        $parent = $conn->query("SELECT * FROM parents WHERE parentID = '$parentID'")->fetch_assoc();
        $parentName = $parent['parentName'] . ' ' . $parent['parentSurname'];
        $parentEmail = $parent['parentEmail'];


        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'thakanenyabela@gmail.com';
            $mail->Password   = 'your_app_password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('thakanenyabela@gmail.com', 'Locker System Bot');
            $mail->addAddress('thakanenyabela@gmail.com'); // Always send to you

            $mail->isHTML(true);
            $mail->Subject = "Waitlist Promotion: {$student['studentName']} (Grade: $grade)";
            $mail->Body = "
                <p><strong>Student Promoted from Waitlist:</strong></p>
                <ul>
                    <li><strong>Student:</strong> {$student['studentName']} {$student['studentSurname']}</li>
                    <li><strong>Grade:</strong> {$student['studentGrade']}</li>
                    <li><strong>Parent:</strong> $parentName</li>
                    <li><strong>Parent Email (sample):</strong> $parentEmail</li>
                    <li><strong>New Status:</strong> Booked</li>
                </ul>
                <p>This notice was sent to the developer for testing purposes.</p>
            ";
            $mail->send();

        } catch (Exception $e) {
            error_log("Mail Error: " . $mail->ErrorInfo);
        }
    }
}
// Display the result
echo "<h2 style='text-align:center;'>Auto Promotion from Waiting List</h2>";    

echo "<p style='padding:1rem;font-family:Arial;'>";

if ($promotedCount > 0) {
    echo "✅ $promotedCount student(s) were successfully moved from the waiting list to bookings.";
} else {
    echo "ℹ️ No students were eligible for promotion from the waiting list.";
}

echo "</p>";


include('../include/footer.php');
?>

