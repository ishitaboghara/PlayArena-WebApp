<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../db.php';

/* BASE PATH (IMPORTANT FOR ADMIN + USER) */
$base = "/PlayArena_web/";

/* USER INFO */
$is_logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';
$user_role = $_SESSION['user_role'] ?? 'user';
?><!DOCTYPE html><html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlayArena</title><!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<!-- CSS -->
<link rel="stylesheet" href="<?= $base ?>assets/css/style.css?v=3">

</head>
<body><nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm py-3">
    <div class="container">    <!-- LOGO -->
    <a class="navbar-brand fw-bold fs-4" href="<?= $base ?>index.php">
        <span style="color:#ff6a2b;">Play</span>Arena
    </a>

    <!-- MOBILE -->
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">

        <!-- ================= USER MENU ================= -->
        <?php if($user_role !== 'admin'): ?>
        
        <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-4">

            <li><a class="nav-link" href="<?= $base ?>index.php">Home</a></li>
            <li><a class="nav-link" href="<?= $base ?>grounds.php">Grounds</a></li>
            <li><a class="nav-link" href="<?= $base ?>coaching.php">Coaching</a></li>
            <li><a class="nav-link" href="<?= $base ?>sports.php">Sports</a></li>
            <li><a class="nav-link" href="<?= $base ?>about.php">About Club</a></li>

        </ul>

        <?php endif; ?>


        <!-- ================= ADMIN MENU ================= -->
        <?php if($user_role === 'admin'): ?>

        <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-4">

            <li><a class="nav-link fw-semibold" href="<?= $base ?>admin/dashboard.php">Dashboard</a></li>
            <li><a class="nav-link" href="<?= $base ?>admin/add_ground.php">Add Ground</a></li>
            <li><a class="nav-link" href="<?= $base ?>admin/add_coach.php">Add Coach</a></li>
            <li><a class="nav-link" href="<?= $base ?>admin/manage_users.php">Users</a></li>
            <li><a class="nav-link" href="<?= $base ?>admin/manage_bookings.php">Bookings</a></li>

        </ul>

        <?php endif; ?>


        <!-- ================= RIGHT SIDE ================= -->
        <div class="d-flex align-items-center gap-3 ms-lg-4 mt-3 mt-lg-0">

            <?php if($is_logged_in): ?>

                <!-- DROPDOWN -->
                <div class="dropdown">
                    <button class="btn btn-light border rounded-pill px-3 dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($user_name) ?>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow">

                        <?php if($user_role === 'admin'): ?>
                            <li><a class="dropdown-item" href="<?= $base ?>admin/dashboard.php">
                                ⚙️ Admin Dashboard
                            </a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item" href="<?= $base ?>dashboard.php">
                                📊 Dashboard
                            </a></li>
                        <?php endif; ?>

                        <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item text-danger" href="<?= $base ?>logout.php">
                            🚪 Logout
                        </a></li>

                    </ul>
                </div>

            <?php else: ?>

                <!-- USER -->
                <a href="<?= $base ?>login.php" class="btn btn-outline-dark rounded-pill px-4">User Login</a>

                <a href="<?= $base ?>register.php" class="btn btn-premium rounded-pill px-4">Register</a>

                <!-- ADMIN -->
                <a href="<?= $base ?>admin/login.php" class="btn btn-dark rounded-pill px-4">
                    🧑‍💻 Admin
                </a>

            <?php endif; ?>

        </div>

    </div>
</div>

</nav>