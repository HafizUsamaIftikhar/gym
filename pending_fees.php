<?php
require_once "db.php";

// Pending fees fetch (join members + fees + membershippackage)
$sql = "SELECT m.member_id, m.name, m.email, mp.package_name, f.amount, f.month_year, f.status
        FROM fees f
        JOIN members m ON f.member_id = m.member_id
        JOIN membershippackage mp ON f.package_id = mp.package_id
        WHERE f.status = 'Pending'
        ORDER BY f.month_year ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Fees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow-lg p-4 border-0">
            <h3 class="text-center text-danger mb-4">⚠️ Pending Fees</h3>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Member ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Package</th>
                            <th>Amount (PKR)</th>
                            <th>Month</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['member_id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['package_name']); ?></td>
                                    <td class="text-success fw-bold">PKR <?php echo $row['amount']; ?></td>
                                    <td><?php echo $row['month_year']; ?></td>
                                    <td><span class="badge bg-danger"><?php echo $row['status']; ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">✅ No Pending Fees</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Back to Dashboard -->
            <div class="text-center mt-4">
                <a href="admin_dashboard.php" class="btn btn-secondary">⬅️ Back to Dashboard</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
