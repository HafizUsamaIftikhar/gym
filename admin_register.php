<?php
require_once __DIR__ . '/db.php'; // apna db.php ka path check kar lena

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // password hash karna
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO admin (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashedPassword);

        if ($stmt->execute()) {
            $success = "✅ Admin registered successfully!";
        } else {
            $error = "❌ Error: " . $conn->error;
        }
    } else {
        $error = "⚠️ Please fill all fields!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center text-primary">Register New Admin</h2>
        <hr>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= $success; ?></div>
        <?php elseif (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success w-100">Register Admin</button>
        </form>

        <div class="text-center mt-3">
            <a href="admin_login.php" class="btn btn-secondary">⬅ Back to Login</a>
        </div>
    </div>
</div>

</body>
</html>
