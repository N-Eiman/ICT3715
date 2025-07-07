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
    <script src="https://kit.fontawesome.com/a2f2a1d6a2.js" crossorigin="anonymous"></script> <!-- Font Awesome for icons -->
    <style>
        body {
            background: #f4f6f9;
        }
        .dashboard-header {
            background: #002244;
            color: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        .dashboard-header h2 {
            margin-bottom: 0;
        }
        .card-hover:hover {
            transform: scale(1.02);
            transition: all 0.2s ease-in-out;
        }
    </style>
</head>
<body>
<div class="container mt-4">

    <div class="dashboard-header shadow-sm">
        <h2><i class="fas fa-lock"></i> Admin Dashboard</h2>
        <p class="lead">Welcome, <?= $_SESSION['adminName'] ?>. Manage and track locker applications efficiently.</p>
    </div>

    <div class="row g-4">
        <!-- Reports -->
        <div class="col-md-4">
            <div class="card card-hover border-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-chart-bar text-primary"></i> MIS Reports</h5>
                    <p class="card-text">Locker usage, payment tracking, student lists & timelines.</p>
                    <a href="admin_locker_report.php" class="btn btn-sm btn-outline-primary">Open Reports</a>
                </div>
            </div>
        </div>

        <!-- Manage Lockers (Future Feature) -->
        <div class="col-md-4">
            <div class="card card-hover border-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-cubes text-success"></i> Locker Management</h5>
                    <p class="card-text">Assign lockers, manage capacity, and monitor grade limits.</p>
                    <a href="#" class="btn btn-sm btn-outline-success disabled">Coming Soon</a>
                </div>
            </div>
        </div>

        <!-- Promote from Waiting List -->
        <div class="col-md-4">
            <div class="card card-hover border-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-user-clock text-warning"></i> Promote Waitlist</h5>
                    <p class="card-text">Move students from waitlist to active booking if space opens.</p>
                    <a href="../parents/auto_promote_waitlist.php" class="btn btn-sm btn-outline-warning">Promote Now</a>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mt-5">
        <a href="../logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

</div>
</body>
</html>
