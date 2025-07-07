<?php
session_start();
include('../include/header.php');
include('../include/db_connect.php');

if (!isset($_SESSION['parentID'])) {
    header("Location: parent_login.php");
    exit;
}

if (isset($_POST['addStudent'])) {
    $name = $_POST['studentName'];
    $surname = $_POST['studentSurname'];
    $grade = $_POST['studentGrade'];
    $schoolNumber = $_POST['studentSchoolNumber'];

    // Check for duplicates
    $check = $conn->query("SELECT * FROM student WHERE studentSchoolNumber = '$schoolNumber'");
    if ($check->num_rows > 0) {
        echo "<div class='alert alert-danger'>This student school number is already in use.</div>";
    } else {
        $conn->query("INSERT INTO student (studentSchoolNumber, studentName, studentSurname, studentGrade, parentID)
                      VALUES ('$schoolNumber', '$name', '$surname', '$grade', '$parentID')");
        echo "<div class='alert alert-success'>Student registered successfully!</div>";
        // Refresh to show updated list
        echo "<meta http-equiv='refresh' content='1'>";
    }
}


$parentID = $_SESSION['parentID'];
$parentName = $_SESSION['parentName'];

// Fetch students linked to this parent
$sql = "SELECT * FROM student WHERE parentID = '$parentID'";
$students = $conn->query($sql);

// Fetch lockers booked to determine availability by grade
$grade8Booked = $conn->query("SELECT COUNT(*) AS total FROM student s
                              JOIN bookings b ON s.studentSchoolNumber = b.recordID
                              WHERE s.studentGrade = 'Grade 8'")->fetch_assoc()['total'];

$grade12Booked = $conn->query("SELECT COUNT(*) AS total FROM student s
                               JOIN bookings b ON s.studentSchoolNumber = b.recordID
                               WHERE s.studentGrade = 'Grade 12'")->fetch_assoc()['total'];

?>


<body class="bg-light">
    <div class="container">
        <h3 class="text-center mb-4">Welcome, <?= htmlspecialchars($parentName) ?></h3>

        <h5 class="mt-4">Register a New Student</h5>
<form method="POST" class="row g-3 mb-4" action="">
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

        <h5>Your Registered Students</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Grade</th>
                    <th>Locker Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $students->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['studentName'] . ' ' . $row['studentSurname'] ?></td>
                        <td><?= $row['studentGrade'] ?></td>

                        <?php
                        $studentID = $row['studentSchoolNumber'];
                        $grade = $row['studentGrade'];

                        // Check locker booking status
                        $bookingCheck = $conn->query("SELECT * FROM bookings WHERE recordID = '$studentID'")->num_rows;
                        $waitlistCheck = $conn->query("SELECT * FROM waitinglist WHERE recordID = (SELECT recordID FROM adminofficer WHERE studentSchoolNumber = '$studentID' LIMIT 1)")->num_rows;
                        ?>

                        <td>
                            <?php
                            if ($bookingCheck > 0) echo "Booked";
                            elseif ($waitlistCheck > 0) echo "Waiting List";
                            else echo "Not Applied";
                            ?>
                        </td>
                        <td>
                            <?php if ($bookingCheck == 0 && $waitlistCheck == 0): ?>
                                <a href="apply_locker.php?student=<?= $studentID ?>" class="btn btn-success btn-sm">Apply for Locker</a>
                            <?php else: ?>
                                <a href="cancel_application.php?student=<?= $studentID ?>" class="btn btn-danger btn-sm">Cancel</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <p><a href="logout.php" class="btn btn-secondary">Logout</a></p>
    </div>
<?php
include('../include/footer.php'); ?>

