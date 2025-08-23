
<?php
session_start();
include('../include/header.php');
include('../include/db_connect.php');;

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $parentID = $_POST['parentID'];
    $parentTitle = $_POST['parentTitle'];
    $parentName = $_POST['parentName'];
    $parentSurname = $_POST['parentSurname'];
    $parentEmail = $_POST['parentEmail'];
    $homeAddress = $_POST['homeAddress'];
    $phoneNumber = $_POST['phoneNumber'];
    $password = $_POST['password'];

    // Basic validation
    if (strlen($parentID) != 13) {
        $error = "South African ID must be 13 digits.";
    } else {
        $sql = "INSERT INTO parents (parentID, parentTitle, parentName, parentSurname, parentEmail, homeAddress, phoneNumber, password)
                VALUES ('$parentID', '$parentTitle', '$parentName', '$parentSurname', '$parentEmail', '$homeAddress', '$phoneNumber', '$password')";

        if ($conn->query($sql) === TRUE) {
            echo "
                    <script>
                        alert('Registration successful! You may now log in.');
                        window.location.href = 'parent_login.php';
                    </script>
                ";
                exit; 
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>


<body class="bg-dark-subtle text-dark-emphasis">
    <div class="container">
        <h2 class="text-center fw-bold mb-4">Parent Registration</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="d-flex row" autocomplete="off">
            <div class="col-md-2">
                <label class="form-label">Title</label>
                <select name="parentTitle" class="form-select" required>
                    <option value="">Select</option>
                    <option>Mr</option>
                    <option>Mrs</option>
                    <option>Ms</option>
                    <option>Dr</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">First Name</label>
                <input type="text" name="parentName" class="form-control" required>
            </div>
            <div class="col-md-5">
                <label class="form-label">Surname</label>
                <input type="text" name="parentSurname" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email Address</label>
                <input type="email" name="parentEmail" class="form-control" required autocomplete="off">
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phoneNumber" class="form-control" required>
            </div>
            <div class="col-md-12">
                <label class="form-label">Home Address</label>
                <input type="text" name="homeAddress" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">13-Digit ID</label>
                <input type="text" name="parentID" class="form-control" maxlength="13" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" minlength="6" required autocomplete="new-password">
            </div>
            <div class="d-grid">
                <button class="btn mt-4" style="background-color: #a0005d; color: #f8d7da;" type="submit">Register</button>
            </div>
        </form>
    </div>
<?php include('../include/footer.php'); ?>
