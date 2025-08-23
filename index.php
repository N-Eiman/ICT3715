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
 <style>
      /* ===== Reset ===== */
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background: url('images/locker.jpg') no-repeat center center/cover;
        position: relative;
        color: #fff;
      }

      /* Overlay for readability */
      body::before {
        content: "";
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.4);
        z-index: 0;
      }

      nav.navbar {
        background: rgba(160, 0, 93, 0.9);
        backdrop-filter: blur(4px);
        padding: 0.8rem 1rem;
        z-index: 2;
      }

      nav.navbar .navbar-brand {
        font-weight: 600;
        color: #fff;
      }

      nav.navbar .nav-link {
        color: #f8d7da;
        transition: color 0.3s ease;
      }

      nav.navbar .nav-link:hover {
        color: #ffd700;
      }

      main {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        position: relative;
        padding: 20px;
      }

      .content-box {
        background: rgba(255, 255, 255, 0.1);
        padding: 30px;
        border-radius: 12px;
        backdrop-filter: blur(6px);
        max-width: 650px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
      }

      .content-box h2 {
        font-size: 1.8rem;
        margin-bottom: 15px;
        color: #ffd700;
      }

      .content-box p {
        font-size: 1rem;
        margin-bottom: 25px;
        color: #f1f1f1;
      }

      .btn-custom {
        background: #ffd700;
        color: #333;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
      }

      .btn-custom:hover {
        background: #ffcc00;
        transform: translateY(-2px);
      }

      footer {
        background: rgba(160, 0, 93, 0.9);
        text-align: center;
        padding: 10px;
        font-size: 0.9rem;
        z-index: 2;
      }
    </style>
  </head>
  <body>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    ?>

    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid">
        <a class="navbar-brand" href="/index.php">Centurion Locker System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">

            <?php if (isset($_SESSION['parentID'])): ?>
              <!-- Logged-in Parent -->
              <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/students/locker_availability.php">Locker Availability</a></li>
              <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/parent_dashboard.php">Dashboard</a></li>
              <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/logout.php">Logout</a></li>
            <?php else: ?>
            <!-- Guest (Not logged in) -->
            <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/parents/parent_register.php">Register</a></li>
            <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/parents/parent_login.php">Parent Sign In</a></li>
            <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/admin/admin_login.php">Admin Login</a></li>
          <?php endif; ?>

          </ul>
        </div>
      </div>
    </nav>

    <main>
      <div class="content-box">
        <h2>Welcome to the Centurion Locker Booking System</h2>
        <p>Book and manage your school lockers quickly and easily online.</p>
        
          <a href="/centurion-locker-website/parents/parent_login.php" class="btn-custom">Parent Login</a>
          <a href="/centurion-locker-website/admin/admin_login.php" class="btn-custom">Admin Login</a>
          <a href="/centurion-locker-website/parents/parent_register.php" class="btn-custom">Parent Register</a>
          
       
      </div>
    </main>

    <footer>
      &copy; <?php echo date("Y"); ?> Centurion Locker System. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>