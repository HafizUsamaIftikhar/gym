<?php
session_start();
require_once __DIR__ . '/db.php';

// ✅ Update logout time for this session
if (isset($_SESSION['login_id'])) {
    $log_id = $_SESSION['login_id'];
    $sql = "UPDATE login_logs SET logout_time = NOW() WHERE login_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $log_id);
    $stmt->execute();
}

// Destroy session
session_unset();
session_destroy();

header("Location: login.php");
exit;
?>
