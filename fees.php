<?php
session_start();
if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/db.php';

$member_id = $_SESSION['member_id'];
$message = "";

// Agar form submit hua
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $month_year = $_POST['month_year'];
    $package_id = $_POST['package_id'];

    // Package ka amount nikalna
    $stmt = $conn->prepare("SELECT price FROM membershippackage WHERE package_id = ?");
    $stmt->bind_param("i", $package_id);
    $stmt->execute();
    $stmt->bind_result($amount);
    $stmt->fetch();
    $stmt->close();

    if ($amount) {
        // Fees insert karna
        $sql = "INSERT INTO fees (member_id, package_id, month_year, amount, status, payment_date) 
                VALUES (?, ?, ?, ?, 'Paid', NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisd", $member_id, $package_id, $month_year, $amount);

        if ($stmt->execute()) {
            // Membership update karna
            $update = $conn->prepare("UPDATE members SET package_id = ? WHERE member_id = ?");
            $update->bind_param("ii", $package_id, $member_id);
            $update->execute();

            header("Location: fees_history.php");
            exit;
        } else {
            $message = "❌ Error saving fees: " . $stmt->error;
        }
    } else {
        $message = "❌ Invalid package selected.";
    }
}

// Membership packages fetch karna
$packages = $conn->query("SELECT package_id, package_name, duration, price FROM membershippackage");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pay Fees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="dashboard.php">🏋️ Gym System</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="membership.php">Membership</a></li>
                <li class="nav-item"><a class="nav-link active" href="fees.php">Pay Fees</a></li>
                <li class="nav-item"><a class="nav-link" href="fees_history.php">Fees History</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page Content -->
<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h3 class="text-primary mb-3">Pay Your Fees</h3>

        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="fees.php" method="POST">

            <!-- Select Month -->
            <div class="mb-3">
                <label for="month_year" class="form-label">Select Month</label>
                <input type="month" id="month_year" name="month_year" class="form-control" required>
            </div>

            <!-- Select Package -->
            <div class="mb-3">
                <label for="package_id" class="form-label">Select Package</label>
                <select name="package_id" id="package_id" class="form-select" required>
                    <option value="">-- Choose Package --</option>
                    <?php while ($row = $packages->fetch_assoc()): ?>
                        <option value="<?php echo $row['package_id']; ?>">
                            <?php echo $row['package_name']; ?> - <?php echo $row['duration']; ?> Months (PKR <?php echo $row['price']; ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-success w-100">💰 Pay Now</button>
        </form>

        <!-- Back to Dashboard -->
        <div class="text-center mt-3">
            <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
