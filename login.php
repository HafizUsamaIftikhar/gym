<?php
session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM members WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();

    if ($member && password_verify($password, $member['password'])) {
        $_SESSION['member_id'] = $member['member_id'];
        $_SESSION['member_name'] = $member['name'];

        // ✅ Insert Login Log
        $log_sql = "INSERT INTO login_logs (member_id, login_time) VALUES (?, NOW())";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("i", $member['member_id']);
        $log_stmt->execute();

        // Store log id for logout update
        $_SESSION['login_id'] = $conn->insert_id;

        header("Location: dashboard.php");
        exit;
    } else {
        echo "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Gym</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: #fff;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
            width: 350px;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .form-container button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container button:hover {
            background: #0056b3;
        }
        .error {
            color: red;
            text-align: center;
            margin: 10px 0;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
        }
        .register-link a {
            color: #007bff;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Member Login</h2>
        <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register Here</a></p>
        </div>
    </div>
</body>
</html>
