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

<body style="font-family: monospace, sans-serif;
    background: #bbe4e9; color: #5585b5;"> 
<div class="container col-md-6"style="margin: 50px auto 95px;color: #5585b5; font-size: 20px; background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(6px); padding: 20px; border-radius: 10px;">
    <h1 class="text-center fw-bold mb-4" style="color: #5585b5;">Admin Login</h1>
    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST" action=""  autocomplete="off">
        <div class="mb-3">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" required autocomplete="off">
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required autocomplete="new-password">
        </div>
        <div class="d-grid">
        <button class="btn mt-4" style="background-color: #5585b5; border-color: #5585b5 ; color: #bbe4e9; font-size: 20px;" type="submit">Login</button>
        </div>
    </form>
    <p class="text-center mt-3 text-black">
            Only registered accounts can login
        </p>
</div>
</body>

<?php include('../include/footer.php'); ?>
