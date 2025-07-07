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
    <link rel="stylesheet" href="../styles.css">
    <title>Book A Locker</title>

    <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg m-0" style="background-color: #a0005d; color: #f8d7da;;" fixed-top shadow">
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
          <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/parents/auto_promote_waitlist.php">WaitListPromotion</a></li>
          <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/parents/cancel_application.php">Cancel Application</a></li>
          <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/parents/logout.php">Logout</a></li>
          


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

        <?php if (isset($_SESSION['admin_id']) || isset($_SESSION['parent_id'])): ?>
          <li class="nav-item">
            <a class="nav-link btn btn-outline-danger ms-2 px-3" href="/logout.php">Logout</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>


    