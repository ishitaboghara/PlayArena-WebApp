<?php
session_start();

// ✅ NOT LOGGED IN
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

// ✅ NOT ADMIN
if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'){
    header("Location: ../index.php");
    exit;
}
?>