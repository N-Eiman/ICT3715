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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f4f6f9;
        }

        /* ===== Navbar ===== */
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

        /* ===== Dashboard Header ===== */
        .dashboard-header {
            background: linear-gradient(135deg, #a0005d, #6b003c);
            color: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .dashboard-header h2 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
        }

        /* ===== Cards ===== */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }

        .card-header {
            font-weight: 600;
            background: #ffd700;
            border-bottom: none;
            color: #333;
        }

        /* ===== Footer ===== */
        footer {
            background: rgba(160, 0, 93, 0.9);
            text-align: center;
            padding: 10px;
            font-size: 0.9rem;
            margin-top: auto;
            color: white;
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
            <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="admin_locker_report.php">Locker Reports</a></li>
            <li class="nav-item"><a class="nav-link" href="admin_logout.php">Logout</a></li>
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
            <!-- Example Card -->
            <div class="col-md-4">
                <div class="card card-hover">
                    <div class="card-header">Locker Management</div>
                    <div class="card-body">
                        <p>View and manage locker allocations and availability.</p>
                        <a href="admin_locker_report.php" class="btn btn-warning">View Report</a>
                    </div>
                </div>
            </div>

            <!-- Example Card -->
            <div class="col-md-4">
                <div class="card card-hover">
                    <div class="card-header">Parent Accounts</div>
                    <div class="card-body">
                        <p>Manage registered parent accounts and their linked students.</p>
                        <a href="#" class="btn btn-warning">Manage Parents</a>
                    </div>
                </div>
            </div>

            <!-- Example Card -->
            <div class="col-md-4">
                <div class="card card-hover">
                    <div class="card-header">Payments</div>
                    <div class="card-body">
                        <p>Review and update payment statuses for bookings.</p>
                        <a href="#" class="btn btn-warning">View Payments</a>
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