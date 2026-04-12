<?php
include 'auth.php';
include '../includes/header.php';
?><div class="container py-5"><!-- TITLE -->
<div class="text-center mb-5">
    <h2 class="fw-bold display-6">⚙️ Admin Dashboard</h2>
    <p class="text-muted">Manage grounds, coaches, users & bookings</p>
</div>

<!-- CARDS -->
<div class="row g-4 justify-content-center">

    <!-- ADD GROUND -->
    <div class="col-lg-3 col-md-6">
        <a href="add_ground.php" class="admin-card">
            <div class="icon">🏟</div>
            <h5>Add Ground</h5>
            <p>Manage sports venues</p>
        </a>
    </div>

    <!-- ADD COACH -->
    <div class="col-lg-3 col-md-6">
        <a href="add_coach.php" class="admin-card">
            <div class="icon">🏋️</div>
            <h5>Add Coach</h5>
            <p>Manage coaching staff</p>
        </a>
    </div>

    <!-- USERS -->
    <div class="col-lg-3 col-md-6">
        <a href="manage_users.php" class="admin-card">
            <div class="icon">👤</div>
            <h5>Users</h5>
            <p>View all users</p>
        </a>
    </div>

    <!-- BOOKINGS -->
    <div class="col-lg-3 col-md-6">
        <a href="manage_bookings.php" class="admin-card">
            <div class="icon">📅</div>
            <h5>Bookings</h5>
            <p>Track reservations</p>
        </a>
    </div>

</div>

</div><!-- PREMIUM STYLES --><style>
.admin-card{
    display:block;
    background:#fff;
    padding:30px;
    border-radius:18px;
    text-align:center;
    text-decoration:none;
    color:#111;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
    transition:0.35s;
    height:100%;
}

.admin-card .icon{
    font-size:40px;
    margin-bottom:15px;
}

.admin-card h5{
    font-weight:700;
    margin-bottom:8px;
}

.admin-card p{
    font-size:14px;
    color:#777;
}

.admin-card:hover{
    transform:translateY(-8px) scale(1.03);
    box-shadow:0 20px 50px rgba(0,0,0,0.2);
}

/* DIFFERENT COLORS ON HOVER */
.admin-card:nth-child(1):hover{
    background:linear-gradient(135deg,#ff6a2b,#ff9a3c);
    color:#fff;
}
.admin-card:nth-child(2):hover{
    background:linear-gradient(135deg,#28a745,#5cd67a);
    color:#fff;
}
.admin-card:nth-child(3):hover{
    background:linear-gradient(135deg,#007bff,#4dabf7);
    color:#fff;
}
.admin-card:nth-child(4):hover{
    background:linear-gradient(135deg,#6f42c1,#9b6bff);
    color:#fff;
}
</style><?php include '../includes/footer.php'; ?>