<?php 
include('include/db_connect.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>Book A Locker</title>
<body style="background-image: url('images/locker.jpg'); background-color: #f0f0f0;">
    <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg fixed-top shadow">
  <div class="container-fluid">
    <a class="navbar-brand" href="/index.php">Centurion Locker System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/index.php">Home</a></li>

        <?php if (isset($_SESSION['parentID'])): ?>
          <!-- Logged-in Parent -->
          <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/students/locker_availability.php">Locker Availability</a></li>
          <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/parents/parent_dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/parents/apply_locker.php">Locker Application</a></li>
          <li class="nav-item"><a class="nav-link disabled" aria-disabled="true" href="/centurion-locker-website/students/confirmation.php">Confirmation</a></li>
          <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/parents/auto_promote_waitlist.php">WaitList Promotion</a></li>
          <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/parents/cancel_application.php">Cancel Application</a></li>
          


        <?php elseif (isset($_SESSION['adminID'])): ?>
          <!-- Logged-in Admin -->
          <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/admin/locker_management.php">Locker Management</a></li>
          <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/admin/admin_locker_report.php">MIS Reports</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/admin_dashboard.php">Admin Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin_logout.php">Logout</a></li>

        <?php else: ?>
          <!-- Guest (Not logged in) -->
          <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/index.php">Welcome Page</a></li>
          <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/parents/parent_register.php">Register</a></li>
          <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/parents/parent_login.php">Parent Sign In</a></li>
          <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/admin/admin_login.php">Admin Login</a></li>
        <?php endif; ?>

        <?php if (isset($_SESSION['adminID']) || isset($_SESSION['parentID'])): ?>
          <li class="nav-item">
            <a class="nav-link btn btn-outline-danger ms-2 px-3" href="/logout.php">Logout</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
    
    <div class="container">
      <h1 class="text-center mt-5" >Welcome to Centurion High Locker Booking System</h1>
      <h2>
        All booking applications are handled here and should be completed by end
        of November 2025 for the following year
      </h2>
      <h3 class="text-center mt-3">
        Please note that The School have limited locker space for Grade 8 and
        Grade 12.
      </h3>
      <div class="d-grid m-5">
        <a href="/centurion-locker-website/students/locker_availability.php" class="btn" style="background-color: #f8d7da; color: #a0005d; margin-bottom: 30px;">View Available Lockers</a>
      <a href="/centurion-locker-website/parents/parent_register.php" class="btn" style="background-color: #a0005d; color: #f8d7da;">Register</a>
    </div>
  
<footer class="text-center mt-2">
  <p style="color: #000; padding-top: 50px; font-weight: bold">&copy; 2025 CLBS</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>