<?php
session_start();
include('../include/header.php');
include('../include/db_connect.php');

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $adminEmail = $_POST['adminEmail'];
    $password = $_POST['password'];

    $query = $conn->query("SELECT * FROM adminofficer WHERE adminEmail = '$adminEmail' AND password = '$password' LIMIT 1");

    if ($query && $query->num_rows > 0) {
        $admin = $query->fetch_assoc();
        $_SESSION['adminID'] = $admin['adminID'];
        $_SESSION['adminName'] = $admin['adminName'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid login credentials. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4">🔐 Admin Login - Locker Booking System</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="admin_dashboard.php" class="p-4 bg-white shadow rounded" autocomplete="off">
        <div class="mb-3">
            <label>Email Address</label>
            <input type="email" name="adminEmail" class="form-control" required autocomplete="off">
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required autocomplete="new-password">
        </div>
        <button class="btn btn-primary" type="submit">Login</button>
        <a href="../index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php include('../include/footer.php'); ?>
