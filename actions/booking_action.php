<?php
session_start();
require_once '../db.php';

header('Content-Type: application/json');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$action = $_POST['action'] ?? '';


// ================== GROUND BOOKING ==================
if($action === 'confirm_booking') {

    $user_id = $_SESSION['user_id'] ?? 0;

    if(!$user_id){
        echo json_encode(['success'=>false,'message'=>'User not logged in']);
        exit;
    }

    $ground_id = (int)($_POST['ground_id'] ?? 0);
    $booking_date = $_POST['booking_date'] ?? '';
    $start = $_POST['start_time'] ?? '';
    $end = $_POST['end_time'] ?? '';
    $total_amount = (float)($_POST['total_amount'] ?? 0);

    if(!$ground_id || !$booking_date || !$start || !$end || $total_amount <= 0){
        echo json_encode(['success'=>false,'message'=>'Invalid booking data']);
        exit;
    }

    $booking_date = $conn->real_escape_string($booking_date);
    $slot_time = $conn->real_escape_string(trim($start).' - '.trim($end));

    $query = "INSERT INTO bookings 
        (user_id, ground_id, booking_date, slot_time, total_amount, status)
        VALUES ($user_id, $ground_id, '$booking_date', '$slot_time', $total_amount, 'confirmed')";

    echo json_encode(['success'=>$conn->query($query)]);
    exit;
}


// ================== COACH BOOKING ==================
if($action === 'confirm_coach_booking') {

    $user_id = $_SESSION['user_id'] ?? 0;

    if(!$user_id){
        echo json_encode(['success'=>false,'message'=>'Login required']);
        exit;
    }

    $coach_id = (int)($_POST['coach_id'] ?? 0);
    $booking_date = $_POST['booking_date'] ?? '';
    $start = $_POST['start_time'] ?? '';
    $end = $_POST['end_time'] ?? '';
    $total_amount = (float)($_POST['total_amount'] ?? 0);

    if(!$coach_id || !$booking_date || !$start || !$end || $total_amount <= 0){
        echo json_encode(['success'=>false,'message'=>'Invalid data']);
        exit;
    }

    $booking_date = $conn->real_escape_string($booking_date);
    $slot_time = $conn->real_escape_string(trim($start).' - '.trim($end));

    $query = "INSERT INTO bookings 
        (user_id, coach_id, booking_date, slot_time, total_amount, status)
        VALUES ($user_id, $coach_id, '$booking_date', '$slot_time', $total_amount, 'confirmed')";

    echo json_encode(['success'=>$conn->query($query)]);
    exit;
}


// ================== CANCEL ==================
if($action === 'cancel_booking') {

    $id = (int)$_POST['id'];
    $user_id = $_SESSION['user_id'];

    $query = "UPDATE bookings 
              SET status='cancelled' 
              WHERE id=$id AND user_id=$user_id";

    echo json_encode(['success'=>$conn->query($query)]);
    exit;
}


echo json_encode(['success'=>false,'message'=>'Invalid action']);
exit;
?>