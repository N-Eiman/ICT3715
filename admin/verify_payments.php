<?php
session_start();
if (!isset($_SESSION['adminID'])) {
    header("Location: admin_login.php");
    exit;
}

include '../include/db_connect.php';
include '../include/header.php';

$message = '';

// ---------------------- TRANSFER PAYMENTS ----------------------
$bookedStudents = $conn->query("SELECT studentSchoolNumber, parentID FROM bookings WHERE lockersID IS NOT NULL");
while ($row = $bookedStudents->fetch_assoc()) {
    $studentNumber = (int)$row['studentSchoolNumber'];
    $parentID = (int)$row['parentID'];
    $filePath = "C:\\Users\\Nita\\payment\\payment_{$studentNumber}.pdf";
    $amount = 100;

    $check = $conn->query("SELECT paymentID FROM payments WHERE studentSchoolNumber = $studentNumber LIMIT 1");
    if ($check->num_rows == 0) {
        $stmt = $conn->prepare("
            INSERT INTO payments (FilePath, studentSchoolNumber, parentID, amount, paymentStatus, paymentDate)
            VALUES (?, ?, ?, ?, 'Pending', NOW())
        ");
        $stmt->bind_param("siii", $filePath, $studentNumber, $parentID, $amount);
        $stmt->execute();
        $stmt->close();
    }
}

// ---------------------- HANDLE PAYMENT VERIFICATION ----------------------
if (isset($_POST['verifyPayment'], $_POST['paymentID'])) {
    $paymentID = (int) $_POST['paymentID'];
    $stmt = $conn->prepare("UPDATE payments SET paymentStatus = 'Paid' WHERE paymentID = ?");
    $stmt->bind_param("i", $paymentID);
    if ($stmt->execute()) {
        $message = "Payment ID $paymentID marked as Paid.";
    } else {
        $message = "Failed to verify payment: " . $stmt->error;
    }
    $stmt->close();
}

// ---------------------- FETCH PAYMENTS ----------------------
$payments = $conn->query("SELECT paymentID, studentSchoolNumber, FilePath, amount, paymentStatus, paymentDate FROM payments ORDER BY paymentDate DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Payments Management | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: monospace, sans-serif; background:#f0f8ff; color:#333; padding:20px; }
.alert { font-weight:600; }
.scroll-table { max-height:500px; overflow-y:auto; display:block; }
table { min-width:100%; border-collapse: collapse; }
th, td { white-space: nowrap; }
</style>
</head>
<body>
<div class="container">
    <h2>Payments Management</h2>
    <?php if(!empty($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="scroll-table">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Student Number</th>
                    <th>File</th>
                    <th>Amount (R)</th>
                    <th>Status</th>
                    <th>Payment Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php $rowCount = 0; ?>
            <?php while($p = $payments->fetch_assoc()): ?>
                <?php $rowCount++; ?>
                <tr>
                    <td><?= (int)$p['paymentID'] ?></td>
                    <td><?= (int)$p['studentSchoolNumber'] ?></td>
                    <td><a href="<?= htmlspecialchars($p['FilePath']) ?>" target="_blank">View PDF</a></td>
                    <td><?= number_format($p['amount'], 2) ?></td>
                    <td><?= htmlspecialchars($p['paymentStatus']) ?></td>
                    <td><?= htmlspecialchars($p['paymentDate']) ?></td>
                    <td>
                        <?php if($p['paymentStatus'] === 'Pending'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="paymentID" value="<?= $p['paymentID'] ?>">
                                <button type="submit" name="verifyPayment" class="btn btn-success btn-sm">Verify</button>
                            </form>
                        <?php else: ?>
                            <span class="text-success">Paid</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
<?php include '../include/footer.php'; ?>
