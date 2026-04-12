<?php
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    if ($_POST['action'] === 'check_conflict') {
        $ground_id = (int)$_POST['ground_id'];
        $booking_date = $_POST['booking_date'];
        $slot_time = $_POST['slot_time'];

        $stmt = $conn->prepare("SELECT id FROM bookings WHERE ground_id = ? AND booking_date = ? AND slot_time = ? AND status != 'Cancelled'");
        $stmt->bind_param("iss", $ground_id, $booking_date, $slot_time);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(['conflict' => true]);
        } else {
            echo json_encode(['conflict' => false]);
        }
        $stmt->close();
        exit;
    }
}

// Fallback
echo json_encode(['error' => 'Invalid request']);
