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

<body style="background-image: url('../images/locker.jpg'); background-size: cover; background-attachment: fixed; background-position: center;">
    <div class="container col-md-6" style="margin-top: 50px;color: #ffd700; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(6px); padding: 20px; border-radius: 10px;">
        <h1 class="text-center fw-bold  mb-4">Parent Login</h1>
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
                <button class="btn mt-4" style="background-color: #ffd700;; color: #fff;" type="submit">Login</button>
            </div>
        </form>
        <p class="text-center mt-3 text-white">
            New here? <a href="parent_register.php" style="color: #fff;">Register as a Parent</a>
        </p>
    </div>
        </body>
<?php
include('../include/footer.php'); ?>
