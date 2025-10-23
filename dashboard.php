<?php
session_start();
if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/db.php';

$member_id = $_SESSION['member_id'];

// Get user info
$sql = "SELECT m.name, mp.package_name, mp.duration, mp.price 
        FROM members m
        LEFT JOIN membershippackage mp ON m.package_id = mp.package_id
        WHERE m.member_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php">🏋️ Gym System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="membership.php">Membership</a></li>
                    <li class="nav-item"><a class="nav-link" href="fees.php">Pay Fees</a></li>
                    <li class="nav-item"><a class="nav-link" href="fees_history.php">Fees History</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="container mt-5">
        <div class="row g-4">

            <!-- Welcome Card -->
            <div class="col-md-6">
                <div class="card shadow-lg p-4 text-center">
                    <h2 class="text-primary">Welcome, <?php echo htmlspecialchars($user['name']); ?> 👋</h2>
                    <p class="text-muted">This is your gym dashboard.</p>
                </div>
            </div>

            <!-- Membership Summary Card -->
            <div class="col-md-6">
                <div class="card shadow-lg p-4">
                    <h4 class="text-success">Membership Summary</h4>
                    <hr>
                    <?php if ($user['package_name']): ?>
                        <p><strong>Package:</strong> <?php echo htmlspecialchars($user['package_name']); ?></p>
                        <p><strong>Duration:</strong> <?php echo htmlspecialchars($user['duration']); ?> Months</p>
                        <p><strong>Price:</strong> ₨<?php echo htmlspecialchars($user['price']); ?></p>
                        <a href="membership.php" class="btn btn-outline-primary w-100">View Details</a>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            You don’t have any active membership.
                        </div>
                        <a href="pay_fees.php" class="btn btn-success w-100">Choose a Package</a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>