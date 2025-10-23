<?php
session_start();
if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/db.php';

$member_id = $_SESSION['member_id'];

$sql = "SELECT f.month_year, f.amount, f.status, f.payment_date, mp.package_name
        FROM fees f
        JOIN membershippackage mp ON f.package_id = mp.package_id
        WHERE f.member_id = ?
        ORDER BY f.payment_date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fees History</title>
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
                    <li class="nav-item"><a class="nav-link" href="membership.php">Membership</a></li>
                    <li class="nav-item"><a class="nav-link" href="fees.php">Pay Fees</a></li>
                    <li class="nav-item"><a class="nav-link active" href="fees_history.php">Fees History</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Fees History Table -->
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center text-primary">Your Fees History</h2>
            <hr>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Month</th>
                        <th>Package</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['month_year']); ?></td>
                                <td><?php echo htmlspecialchars($row['package_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['amount']); ?> PKR</td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo htmlspecialchars($row['payment_date']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center text-danger">No fees history found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <!-- Back to Dashboard Button -->
            <div class="text-center mt-3">
                <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
