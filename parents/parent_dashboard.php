<?php
session_start();
include('../include/header.php');
include('../include/db_connect.php');

if (!isset($_SESSION['parentID'])) {
    header("Location: parent_login.php");
    exit;
}

$parentID = $_SESSION['parentID'];
$parentName = $_SESSION['parentName'] ?? 'Parent';

// Handle student registration
if (isset($_POST['addStudent'])) {
    $name = $_POST['studentName'];
    $surname = $_POST['studentSurname'];
    $grade = $_POST['studentGrade'];
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

<body class="bg-light">
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
            <button type="submit" name="addStudent" class="btn btn-success">Add Student</button>
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
                $fullName = $row['studentName'] . ' ' . $row['studentSurname'];
                $grade = $row['studentGrade'];
                $statusLabel = "";
                $actionHTML = "";

                // Step 1: Get recordID from adminofficer (if any)
                $recordRow = $conn->query("SELECT recordID FROM adminofficer WHERE studentSchoolNumber = '$studentID' LIMIT 1")->fetch_assoc();
                $recordID = $recordRow['recordID'] ?? null;

                // Step 2: Check if student is booked
                $bookingRow = $conn->query("SELECT * FROM bookings WHERE recordID = '$studentID'")->fetch_assoc();
                $isBooked = $bookingRow ? true : false;

                // Step 3: If booked, check payment
                if ($isBooked) {
                    $paymentRow = $conn->query("SELECT paymentStatus FROM payments WHERE studentSchoolNumber = '$studentID'")->fetch_assoc();
                    $paymentStatus = $paymentRow['paymentStatus'] ?? 'Pending';

                    if ($paymentStatus === "Paid") {
                        $statusLabel = "<span class='badge bg-success'>Paid</span>";
                    } else {
                        $statusLabel = "<span class='badge bg-info text-dark'>Booked - Awaiting Payment</span>";
                    }
                    $actionHTML = "<a href='cancel_application.php?student=$studentID' class='btn btn-sm btn-danger'>Cancel</a>";
                }

                // Step 4: If not booked, check waiting list
                elseif ($recordID) {
                    $waitlist = $conn->query("SELECT * FROM waitinglist WHERE recordID = '$recordID'");
                    if ($waitlist->num_rows > 0) {
                        $statusLabel = "<span class='badge bg-warning text-dark'>Waiting List</span>";
                        $actionHTML = "<a href='cancel_application.php?student=$studentID' class='btn btn-sm btn-danger'>Cancel</a>";
                    } else {
                        // Fallback if recordID exists but not on waitlist or booking
                        $statusLabel = "<span class='badge bg-secondary'>Pending Review</span>";
                        $actionHTML = "<a href='cancel_application.php?student=$studentID' class='btn btn-sm btn-danger'>Cancel</a>";
                    }
                }

                // Step 5: Not applied at all
                else {
                    $statusLabel = "<span class='badge bg-secondary'>Not Applied</span>";
                    $actionHTML = "<a href='apply_locker.php?student=$studentID' class='btn btn-sm btn-primary'>Apply</a>";
                }
            ?>
            <tr>
                <td><?= $fullName ?></td>
                <td><?= $grade ?></td>
                <td><?= $statusLabel ?></td>
                <td><?= $actionHTML ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <div class="text-end">
        <a href="logout.php" class="btn btn-secondary">Logout</a>
    </div>
</div>

<?php include('../include/footer.php'); ?>
