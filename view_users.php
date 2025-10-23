<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
require_once __DIR__ . '/db.php';

$sql = "SELECT m.member_id, m.name, m.email, m.phone, mp.package_name 
        FROM members m 
        LEFT JOIN membershippackage mp ON m.package_id = mp.package_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-primary mb-4">👥 Registered Users</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Package</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['member_id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['package_name'] ?? 'Not Selected') ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="admin_dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
</div>
</body>
</html>
