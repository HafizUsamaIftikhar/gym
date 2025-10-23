<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
require_once __DIR__ . '/db.php';

// Fetch fees records with member + package details
$sql = "SELECT m.name AS member_name, mp.package_name, f.amount, f.payment_date, f.status
        FROM fees f
        JOIN members m ON f.member_id = m.member_id
        JOIN membershippackage mp ON f.package_id = mp.package_id
        ORDER BY f.payment_date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fees Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="text-center text-primary mb-4">📊 Fees Status</h2>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Member Name</th>
                        <th>Package</th>
                        <th>Amount (PKR)</th>
                        <th>Payment Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['member_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['package_name']); ?></td>
                                <td><?php echo number_format($row['amount']); ?> PKR</td>
                                <td><?php echo $row['payment_date']; ?></td>
                                <td>
                                    <?php if ($row['status'] === 'Paid'): ?>
                                        <span class="badge bg-success">Paid</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Pending</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No fee records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <a href="admin_dashboard.php" class="btn btn-secondary w-100 mt-3">⬅ Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
