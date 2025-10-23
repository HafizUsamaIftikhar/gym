<?php
session_start();
require_once __DIR__ . '/db.php';

// Get all login logs with member names
$sql = "SELECT l.login_id, m.name, l.login_time, l.logout_time
        FROM login_logs l
        JOIN members m ON l.member_id = m.member_id
        ORDER BY l.login_time DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h3 class="text-center text-primary">📝 Login Logs</h3>
        <table class="table table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Member Name</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['login_id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= $row['login_time'] ?></td>
                        <td><?= $row['logout_time'] ?: '<span class="text-danger">Active</span>' ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="admin_dashboard.php" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
    </div>
</div>
</body>
</html>
