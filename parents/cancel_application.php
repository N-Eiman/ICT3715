<?php
session_start();
include('../include/db_connect.php');
include('auto_promote_waitlist.php');

// Check if parent is logged in

if (!isset($_SESSION['parentID'])) {
    header("Location: parent_login.php");
    exit;
}

$studentID = $_GET['student'] ?? null;

// Get admin recordID
$adminQ = $conn->query("SELECT recordID FROM adminofficer WHERE studentSchoolNumber = '$studentID'");
$adminRow = $adminQ->fetch_assoc();
$recordID = $adminRow['recordID'] ?? null;

if ($recordID) {
    // Delete booking or waitlist
    $conn->query("DELETE FROM bookings WHERE recordID = '$studentID'");
    $conn->query("DELETE FROM waitinglist WHERE recordID = '$recordID'");
    $conn->query("DELETE FROM adminofficer WHERE recordID = '$recordID'");
}

header("Location: parent_dashboard.php");
exit;

include('../include/footer.php');
?>