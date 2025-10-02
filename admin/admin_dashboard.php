<?php
session_start();
if (!isset($_SESSION['adminID'])) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Locker System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a2f2a1d6a2.js" crossorigin="anonymous"></script>

    <style>
        /* ===== Reset ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

       body {
    min-height: 100vh;
    margin: 0;
     font-family: monospace, sans-serif;
    background: #bbe4e9;
    color: #5585b5;
}


        /* ===== Navbar ===== */
        nav.navbar {
            background: #5585b5;
            backdrop-filter: blur(4px);
            padding: 0.8rem 1rem;
            z-index: 2;
        }

        nav.navbar .navbar-brand {
            font-weight: 600;
            color: #bbe4e9;
        }

        nav.navbar .nav-link {
            color: #bbe4e9;
            transition: color 0.3s ease;
        }

        nav.navbar .nav-link:hover {
            color: rgba(255, 215, 0, 1);
        }

        /* ===== Dashboard Header ===== */
        .dashboard-header {
            background: rgba(255, 255, 255, 0.5);
            color: #5585b5;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
            font-size: 20px;
        }

        .dashboard-header h2 {
            margin: 0;
            font-size: 45px;
            font-weight: 900;
        }

        

        /* ===== Cards ===== */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin: 50px auto 10px; 
            color: #5585b5; 
            font-size: 20px; 
            background: rgba(255, 255, 255, 0.5); 
            backdrop-filter: blur(6px); 
            padding: 20px; 
           
        }

        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }

        .card-header {
            border-bottom: none;
            color: #5585b5;
            font-size: 20px;
             background: rgba(255, 255, 255, 0.5);
        }

        .btn {
            max-width: 300px;
            font-size: 20px;
        }

        .btn:hover {
            max-width: 300px;
            background:  rgba(255, 215, 0, 0.5);
        }
      
        /* ===== Footer ===== */
        footer {
            background: #5585b5;
            text-align: center;
            padding: 10px;
            font-size: 0.9rem;
            margin-top: 135px;
            color: #bbe4e9;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid">
        <a class="navbar-brand" href="/index.php">Centurion Locker System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/admin/admin_locker_report.php">MIS Reports</a></li>
          <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/admin/admin_dashboard.php">Admin Dashboard</a></li>
           <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/admin/student_registration.php">Registration</a></li>
          <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/admin/locker_application.php">Locker Management</a></li>
            <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/admin/verify_payments.php">Verify</a></li>
           <li class="nav-item"><a class="nav-link" href="/centurion-locker-website/admin/admin_logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <main class="container my-4">
        <div class="dashboard-header">
            <h2><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>
            <p class="mb-0">Manage lockers, monitor bookings, and view reports.</p>
        </div>

        <div class="row">
            
            <div class="col-md-4">
                <div class="card card-hover">
                    <div class="card-header">Locker Management</div>
                    <div class="card-body">
                        <p>View and manage locker allocations.</p>
                        <a href="admin_locker_report.php" class="btn btn-primary">View</a>
                        <a href="locker_application.php" class="btn btn-primary">Manage</a>
                    </div>
                </div>
            </div>

            <!-- Example Card -->
            <div class="col-md-4">
                <div class="card card-hover">
                    <div class="card-header">Parent Accounts</div>
                    <div class="card-body">
                        <p>Register Students and Apply on their behalf.</p>
                        <a href="student_registration.php" class="btn btn-primary">Register For Students</a>
                         

                    </div>
                </div>
            </div>

            <!-- Example Card -->
            <div class="col-md-4">
                <div class="card card-hover">
                    <div class="card-header">Payments</div>
                    <div class="card-body">
                        <p>Review and update payment statuses for bookings.</p>
                        <a href="verify_payments.php" class="btn btn-primary">Verify Payments</a>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> Centurion Locker System. Admin Panel.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>