<?php
session_start();
include('../include/header.php'); 
include('../include/db_connect.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['parentEmail'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM parents WHERE parentEmail = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $parent = $result->fetch_assoc();
        $_SESSION['parentID'] = $parent['parentID'];
        $_SESSION['parentName'] = $parent['parentName'];
        header("Location: parent_dashboard.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<body class="bg-danger-subtle">
    <div class="container col-md-6">
        <h2 class="text-center mb-4">Parent Login</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST" autocomplete="off">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="parentEmail" class="form-control" required autocomplete="off">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required autocomplete="new-password">
            </div>
            <div class="d-grid">
                <button class="btn mt-4" style="background-color: #a0005d; color: #f8d7da;" type="submit">Login</button>
            </div>
        </form>
        <p class="text-center mt-3">
            New here? <a href="parent_register.php" style="color: #a0005d;">Register as a Parent</a>
        </p>
    </div>
<?php
include('../include/footer.php'); ?>
