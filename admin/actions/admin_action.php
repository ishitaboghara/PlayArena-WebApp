<?php
require '../../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../dashboard.php");
    exit;
}

$action = $_POST['action'] ?? '';

/* ===============================
   ADD GROUND
================================ */
if ($action === 'add_ground') {

    $name = trim($_POST['name'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $sport_id = isset($_POST['sport_id']) ? (int)$_POST['sport_id'] : 0;
    $price = isset($_POST['price']) ? (int)$_POST['price'] : 0;
    $image = trim($_POST['image'] ?? '');

    // Validation
    if (empty($name) || empty($location) || empty($sport_id) || $price <= 0) {
        header("Location: ../add_ground.php?error=1");
        exit;
    }

    // Default image fallback
    if (empty($image)) {
        $image = "https://images.unsplash.com/photo-1517649763962-0c623066013b";
    }

    $stmt = $conn->prepare("
        INSERT INTO grounds (name, location, sport_id, price_per_hour, image_url)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("sssis", $name, $location, $sport_id, $price, $image);

    if ($stmt->execute()) {
        header("Location: ../add_ground.php?success=1");
    } else {
        header("Location: ../add_ground.php?error=2");
    }

    exit;
}


/* ===============================
   ADD COACH
================================ */
if ($action === 'add_coach') {

    $name = trim($_POST['name'] ?? '');
    $sport_id = trim($_POST['sport_id'] ?? '');
    $experience = isset($_POST['experience']) ? (int)$_POST['experience'] : 0;
    $price = isset($_POST['price']) ? (int)$_POST['price'] : 0;
    $image = trim($_POST['image'] ?? '');
    $ground_id = isset($_POST['ground_id']) ? (int)$_POST['ground_id'] : 0;
    $bio = trim($_POST['bio'] ?? '');

    // Validation
    if (empty($name) || empty($sport_id)) {
        header("Location: ../add_coach.php?error=1");
        exit;
    }

    // Default coach image
    if (empty($image)) {
        $image = "https://images.unsplash.com/photo-1609710228159-0fa9bd7c0827";
    }

    $stmt = $conn->prepare("
        INSERT INTO coaches 
        (name, sport_id, experience_years, hourly_rate, image_url, ground_id, bio)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("ssiisis", $name, $sport_id, $experience, $price, $image, $ground_id, $bio);

    if ($stmt->execute()) {
        header("Location: ../add_coach.php?success=1");
    } else {
        header("Location: ../add_coach.php?error=2");
    }

    exit;
}


/* ===============================
   INVALID ACTION
================================ */
header("Location: ../dashboard.php");
exit;
?>