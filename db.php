<?php
$conn = new mysqli("localhost", "root", "", "playarena", 3306);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
$conn->set_charset("utf8mb4");
?>
