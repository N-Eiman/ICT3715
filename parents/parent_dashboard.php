<?php 
session_start();
include('../include/header.php');
include('../include/db_connect.php');

if (!isset($_SESSION['parentID'])) {
    header("Location: parent_login.php");
    exit;
}

$parentID   = $_SESSION['parentID'];
$parentName = $_SESSION['parentName'] ?? 'Parent';

// Handle student registration
if (isset($_POST['addStudent'])) {
    $name         = $_POST['studentName'];
    $surname      = $_POST['studentSurname'];
    $grade        = $_POST['studentGrade'];
    $schoolNumber = $_POST['studentSchoolNumber'];

    $check = $conn->query("SELECT * FROM student WHERE studentSchoolNumber = '$schoolNumber'");
    if ($check->num_rows > 0) {
        echo "<div class='alert alert-danger text-center'>This student school number is already in use.</div>";
    } else {
        $conn->query("INSERT INTO student (studentSchoolNumber, studentName, studentSurname, studentGrade, parentID)
                      VALUES ('$schoolNumber', '$name', '$surname', '$grade', '$parentID')");
        echo "<div class='alert alert-success text-center'>Student registered successfully!</div>";
        echo "<meta http-equiv='refresh' content='1'>";
    }
}

// Fetch all students linked to this parent
$students = $conn->query("SELECT * FROM student WHERE parentID = '$parentID'");
?>

<body style="font-family: monospace, sans-serif;
    background: #bbe4e9; color: #5585b5;">
<div class="container py-4">
    <h3 class="text-center fw-bold mb-4">Welcome, <?= htmlspecialchars($parentName) ?></h3>

    <!-- Register a Student -->
    <h5>Register a New Student</h5>
    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Student Name</label>
            <input type="text" name="studentName" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Student Surname</label>
            <input type="text" name="studentSurname" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Grade</label>
            <select name="studentGrade" class="form-select" required>
                <option value="">Select</option>
                <option>Grade 8</option>
                <option>Grade 9</option>
                <option>Grade 10</option>
                <option>Grade 11</option>
                <option>Grade 12</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Student Number</label>
            <input type="text" name="studentSchoolNumber" class="form-control" required maxlength="6">
        </div>
        <div class="col-12 text-center">
            <button type="submit" name="addStudent" class="btn btn-primary">Add Student</button>
        </div>
    </form>

    <!-- Student Table -->
    <h5>Your Registered Students</h5>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Student Name</th>
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
                $actionHTML  = "<a href='apply_locker.php?student=$studentID' class='btn btn-sm btn-primary'>Apply</a>";

                // --- Check bookings ---
                $bookingRow = $conn->query("SELECT * FROM bookings WHERE studentSchoolNumber = '$studentID'")->fetch_assoc();
                if ($bookingRow) {
                    $lockerID = $bookingRow['lockersID'];
                    $payment  = $conn->query("SELECT paymentStatus FROM payments WHERE studentSchoolNumber = '$studentID'")
                                     ->fetch_assoc()['paymentStatus'] ?? 'Pending';

                    if ($lockerID) {
                        $statusLabel = "<span class='badge bg-primary'>Allocated (Locker $lockerID)</span>";
                    } elseif ($payment === "Paid") {
                        $statusLabel = "<span class='badge bg-primary'>Paid - Awaiting Allocation</span>";
                    } else {
                        $statusLabel = "<span class='badge bg-primary text-light'>Booked - Awaiting Allocation</span>";
                    }

                    $actionHTML = "<a href='cancel_application.php?student=$studentID' class='btn btn-sm btn-danger'>Cancel</a>";
                }

                // --- Check waiting list if no booking ---
                elseif ($conn->query("SELECT * FROM waiting_list WHERE studentSchoolNumber = '$studentID'")->num_rows > 0) {
                    $statusLabel = "<span class='badge bg-warning text-dark'>Waitlisted</span>";
                    $actionHTML  = "<a href='cancel_application.php?student=$studentID' class='btn btn-sm btn-danger'>Cancel</a>";
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
        <a href="logout.php" class="btn btn-primary">Logout</a>
    </div>
</div>

<?php include('../include/footer.php'); ?>
