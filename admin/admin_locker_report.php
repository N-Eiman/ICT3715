<?php
session_start();
include('../include/db_connect.php');

if (!isset($_SESSION['adminID'])) {
    header("Location: ../admin_login.php");
    exit;
}

// Locker limits
$gradeLimits = ['Grade 8' => 10, 'Grade 12' => 5];

// Locker usage
$lockerUsage = [];
foreach ($gradeLimits as $grade => $limit) {
    $count = $conn->query("
        SELECT COUNT(*) AS total
        FROM student s
        JOIN bookings b ON s.studentSchoolNumber = b.recordID
        WHERE s.studentGrade = '$grade'
    ")->fetch_assoc()['total'];

    $lockerUsage[] = [
        'grade' => $grade,
        'booked' => $count,
        'limit' => $limit,
        'available' => $limit - $count
    ];
}

// Total bookings & waitlist
$totalBookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'];
$totalWaiting = $conn->query("SELECT COUNT(*) AS total FROM waitinglist")->fetch_assoc()['total'];

// Students list
$students = $conn->query("SELECT * FROM student ORDER BY studentGrade ASC, studentSurname ASC");

// Payments
$payments = $conn->query("
    SELECT s.studentName, s.studentSurname, s.studentGrade, p.parentName, w.payment
    FROM waitinglist w
    JOIN adminofficer a ON w.recordID = a.recordID
    JOIN student s ON a.studentSchoolNumber = s.studentSchoolNumber
    JOIN parents p ON s.parentID = p.parentID
    ORDER BY s.studentGrade ASC
");

// Timeline
$applications = $conn->query("
    SELECT s.studentName, s.studentSurname, s.studentGrade, b.booking_date, p.parentName
    FROM bookings b
    JOIN student s ON b.recordID = s.studentSchoolNumber
    JOIN parents p ON s.parentID = p.parentID
    ORDER BY b.booking_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Locker Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f7f9fc; }
        .section-title { font-size: 1.3rem; margin-top: 2rem; }
        .card { margin-bottom: 2rem; }
    </style>
</head>
<body class="p-4">
<div class="container">

    <h2 class="mb-4">📊 Locker Booking Management Reports</h2>

    <!-- Section 1: Locker Usage -->
    <div class="card">
        <div class="card-header bg-primary text-white">Locker Usage Summary</div>
        <div class="card-body">
            <p><strong>Total Bookings:</strong> <?= $totalBookings ?> &nbsp;&nbsp;|&nbsp;&nbsp; <strong>Total on Waiting List:</strong> <?= $totalWaiting ?></p>
            <table class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Grade</th>
                        <th>Locker Limit</th>
                        <th>Booked</th>
                        <th>Available</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lockerUsage as $usage): ?>
                        <tr>
                            <td><?= $usage['grade'] ?></td>
                            <td><?= $usage['limit'] ?></td>
                            <td><?= $usage['booked'] ?></td>
                            <td><?= $usage['available'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 2: Student List -->
    <div class="card">
        <div class="card-header bg-dark text-white">Students by Grade</div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>School Number</th>
                        <th>Name</th>
                        <th>Grade</th>
                        <th>Parent ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $students->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['studentSchoolNumber'] ?></td>
                            <td><?= $row['studentName'] . ' ' . $row['studentSurname'] ?></td>
                            <td><?= $row['studentGrade'] ?></td>
                            <td><?= $row['parentID'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 3: Payment Report -->
    <div class="card">
        <div class="card-header bg-success text-white">Locker Payment Report</div>
        <div class="card-body">
            <p class="text-muted">Note: Payments are manually updated via database.</p>
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th>Student</th>
                        <th>Grade</th>
                        <th>Parent</th>
                        <th>Payment (R)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $payments->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['studentName'] . ' ' . $row['studentSurname'] ?></td>
                            <td><?= $row['studentGrade'] ?></td>
                            <td><?= $row['parentName'] ?></td>
                            <td><?= number_format($row['payment'], 2) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 4: Application Timeline -->
    <div class="card">
        <div class="card-header bg-info text-white">Application Timeline</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-info">
                    <tr>
                        <th>Student</th>
                        <th>Grade</th>
                        <th>Parent</th>
                        <th>Booking Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $applications->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['studentName'] . ' ' . $row['studentSurname'] ?></td>
                            <td><?= $row['studentGrade'] ?></td>
                            <td><?= $row['parentName'] ?></td>
                            <td><?= date("d M Y H:i", strtotime($row['booking_date'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <a href="admin_dashboard.php" class="btn btn-secondary">⬅ Back to Admin Dashboard</a>
</div>
</body>
</html>
