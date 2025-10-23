<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once __DIR__ . '/db.php';

// Collect fees form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_POST['member_id'];
    $payment_method = $_POST['payment_method'];
    $month_year = $_POST['month_year'];

    // Step 0: Check if fee already collected for this member & month
    $check = $conn->prepare("SELECT * FROM fees WHERE member_id = ? AND month_year = ?");
    $check->bind_param("is", $member_id, $month_year);
    $check->execute();
    $already = $check->get_result();

    if ($already->num_rows > 0) {
        $error = "❌ This member has already paid fees for $month_year.";
    } else {
        // Step 1: Get member's package info
        $sql = "SELECT m.package_id, mp.price 
                FROM members m
                JOIN membershippackage mp ON m.package_id = mp.package_id
                WHERE m.member_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $package = $result->fetch_assoc();

        if ($package) {
            $package_id = $package['package_id'];
            $amount = $package['price'];

            // Step 2: Insert fees record with month & payment_method
            $sql = "INSERT INTO fees (member_id, package_id, amount, payment_date, status, payment_method, month_year) 
                    VALUES (?, ?, ?, NOW(), 'Paid', ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iidss", $member_id, $package_id, $amount, $payment_method, $month_year);

            if ($stmt->execute()) {
                $success = "✅ Fees collected successfully for $month_year!";
            } else {
                $error = "Error: " . $stmt->error;
            }
        } else {
            $error = "⚠️ This member does not have a valid membership package!";
        }
    }
}

// Fetch members for dropdown
$sql = "SELECT member_id, name FROM members";
$members = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Collect Fees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="mb-4 text-center text-primary">💰 Collect Fees</h2>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php elseif (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <!-- Select Member -->
                <div class="mb-3">
                    <label class="form-label">Select Member</label>
                    <select name="member_id" class="form-select" required>
                        <option value="">-- Choose Member --</option>
                        <?php while ($row = $members->fetch_assoc()): ?>
                            <option value="<?php echo $row['member_id']; ?>">
                                <?php echo htmlspecialchars($row['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Select Month -->
                <div class="mb-3">
                    <label class="form-label">Select Month</label>
                    <input type="month" name="month_year" class="form-control" required>
                </div>

                <!-- Select Payment Method -->
                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="Cash">Cash</option>
                        <option value="Online">Online</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success w-100">Collect Fee</button>
            </form>

            <!-- Back Button -->
            <div class="text-center mt-3">
                <a href="admin_dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
