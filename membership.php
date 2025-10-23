<?php
session_start();
if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/db.php';

$member_id = $_SESSION['member_id'];

// Get Membership + Last Paid Month
$sql = "SELECT m.name, mp.package_name, mp.duration, mp.price, f.month_year
        FROM members m
        LEFT JOIN membershippackage mp ON m.package_id = mp.package_id
        LEFT JOIN fees f ON m.member_id = f.member_id
        WHERE m.member_id = ?
        ORDER BY f.payment_date ASC
        LIMIT 1"; // latest fees record
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();
$membership = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Membership</title>
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
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="membership.php">Membership</a></li>
                    <li class="nav-item"><a class="nav-link" href="fees.php">Pay Fees</a></li>
                    <li class="nav-item"><a class="nav-link" href="fees_history.php">Fees History</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Membership Details -->
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center text-primary">Your Membership Details</h2>
            <hr>
            <?php if ($membership && $membership['package_name']): ?>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($membership['name']); ?></p>
                <p><strong>Package:</strong> <?php echo htmlspecialchars($membership['package_name']); ?></p>
                <p><strong>Duration:</strong> <?php echo htmlspecialchars($membership['duration']); ?> Months</p>
                <p><strong>Price:</strong> PKR <?php echo htmlspecialchars($membership['price']); ?></p>
                <?php if (!empty($membership['month_year'])): ?>
                    <p><strong>Last Paid Month:</strong> <?php echo htmlspecialchars($membership['month_year']); ?></p>
                <?php else: ?>
                    <p class="text-danger"><strong>Last Paid Month:</strong> Not Paid Yet ❌</p>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-warning text-center">
                    You don’t have any active membership.
                </div>
                <a href="fees.php" class="btn btn-success w-100">Choose a Package & Pay Fees</a>
            <?php endif; ?>

            <!-- Back to Dashboard Button -->
            <div class="text-center mt-4">
                <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
