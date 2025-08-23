<?php
session_start();
include('../include/db_connect.php');
include('../include/header.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM adminofficer WHERE adminEmail = '$email' AND password = '$password' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        $_SESSION['adminID'] = $admin['recordID'];
        $_SESSION['adminName'] = $admin['adminName'];
        header("Location: admin_dashboard.php"); // or your actual dashboard filename
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<body style="background-image: url('../images/locker.jpg'); background-size: cover; background-attachment: fixed; background-position: center;">
<div class="container col-md-6"style="margin-top: 50px; color: #ffd700; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(6px); padding: 20px; border-radius: 10px;">
    <h1 class="text-center fw-bold mb-4">Admin Login</h1>
    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST" action=""  autocomplete="off">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required autocomplete="off">
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required autocomplete="new-password">
        </div>
        <div class="d-grid">
        <button class="btn mt-4" style="background-color: #ffd700; border-color: #ffd700 ; color: #fff;" type="submit">Login</button>
        </div>
    </form>
    <p class="text-center mt-3 text-white">
            Only registered accounts can login
        </p>
</div>
</body>

<?php include('../include/footer.php'); ?>
