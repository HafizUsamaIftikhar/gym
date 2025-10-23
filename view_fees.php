<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
require_once __DIR__ . '/db.php';

$sql = "SELECT f.fee_id, m.name, f.month_year, f.amount, f.status, f.payment_date, f.payment_method
        FROM fees f 
        JOIN members m ON f.member_id = m.member_id
        ORDER BY f.payment_date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Fees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-primary mb-4">💰 All Fees Records</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Member</th>
                <th>Month</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Payment Method</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['fee_id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= $row['month_year'] ?></td>
                    <td><?= $row['amount'] ?> PKR</td>
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['payment_date'] ?></td>
                    <td>
                        <?php if ($row['payment_method'] == 'Cash'): ?>
                            💵 Cash
                        <?php else: ?>
                            💳 Online
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="admin_dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
</div>
</body>
</html>
