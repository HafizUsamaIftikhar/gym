<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="admin_dashboard.php">🏋️ Gym Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="container mt-5">
        <div class="row g-4">

            <!-- View Users -->
            <div class="col-md-4">
                <div class="card shadow-lg text-center p-4">
                    <h4 class="text-primary">👥 View Users</h4>
                    <p class="text-muted">See all registered members.</p>
                    <a href="view_users.php" class="btn btn-primary w-100">Go</a>
                </div>
            </div>

            <!-- View Fees -->
            <div class="col-md-4">
                <div class="card shadow-lg text-center p-4">
                    <h4 class="text-success">💰 View Fees</h4>
                    <p class="text-muted">Check all fee records.</p>
                    <a href="view_fees.php" class="btn btn-success w-100">Go</a>
                </div>
            </div>

            <!-- Create Member -->
            <div class="col-md-4">
                <div class="card shadow-lg text-center p-4">
                    <h4 class="text-warning">➕ Create Member</h4>
                    <p class="text-muted">Add new gym members.</p>
                    <a href="create_member.php" class="btn btn-warning w-100">Go</a>
                </div>
            </div>

            <!-- Collect Fees -->
            <div class="col-md-6">
                <div class="card shadow-lg text-center p-4">
                    <h4 class="text-info">📥 Collect Fees</h4>
                    <p class="text-muted">Collect fees from members.</p>
                    <a href="collect_fees.php" class="btn btn-info w-100">Go</a>
                </div>
            </div>

            <!-- Paid / Pending -->
            <div class="col-md-6">
                <div class="card shadow-lg text-center p-4">
                    <h4 class="text-danger">📊 Fees Status</h4>
                    <p class="text-muted">Check paid & pending fees.</p>
                    <a href="fees_status.php" class="btn btn-danger w-100">Go</a>
                </div>
            </div>

            <!-- Login Logs -->
            <div class="col-md-12">
                <div class="card shadow-lg text-center p-4">
                    <h4 class="text-secondary">📝 Login Logs</h4>
                    <p class="text-muted">Track login & logout history of members.</p>
                    <a href="login_logs.php" class="btn btn-secondary w-100">Go</a>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
