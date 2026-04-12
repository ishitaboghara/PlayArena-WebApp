<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $booking_id = (int)$_POST['booking_id'];
    
    // Update booking status to Paid
    $stmt = $conn->prepare("UPDATE bookings SET status = 'Paid' WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->close();
} else {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Success | PlayArena</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="glass-card p-5 text-center">
        <h1 class="text-success mb-3">Payment Successful!</h1>
        <p class="lead">Your booking has been confirmed.</p>
        <p>Booking Reference ID: #<?= $booking_id ?></p>
        <a href="index.php" class="btn btn-primary-glass mt-4">Return Home</a>
    </div>
</body>
</html>
