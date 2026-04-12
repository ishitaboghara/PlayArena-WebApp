<?php 
session_start();
include 'includes/header.php';
require_once 'db.php';

// CHECK LOGIN
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ✅ BLOCK ADMIN FROM USER DASHBOARD
if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'){
    header("Location: admin/dashboard.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// FETCH BOOKINGS
$result = $conn->query("
    SELECT b.*, 
           g.name as ground_name,
           c.name as coach_name,
           COALESCE(g.location, 'Coaching Session') as location,
           s.name as sport_name
    FROM bookings b
    LEFT JOIN grounds g ON b.ground_id = g.id
    LEFT JOIN coaches c ON b.coach_id = c.id
    LEFT JOIN sports s ON 
        (g.sport_id = s.id OR c.sport_id = s.id)
    WHERE b.user_id = $user_id
    ORDER BY b.created_at DESC
");

if(!$result){
    die("SQL ERROR: " . $conn->error);
}
?>

<style>
.booking-card {
    background: #f9fafb;
    border-radius: 16px;
    padding: 18px 22px;
    margin-bottom: 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.25s ease;
    border: 1px solid #e5e7eb;
}

.booking-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    background: #ffffff;
}
.booking-card.show {
    opacity:1;
    transform:translateY(0);
}

.cancel-btn {
    background: #ef4444;
    color: white;
    border: none;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 12px;
    cursor: pointer;
    transition: 0.25s;
}

.cancel-btn:hover {
    background: #dc2626;
    transform: scale(1.05);
}

/* CONFIRM BADGE */
.status-badge {
    background: #22c55e;
    color: white;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}
</style>

<div class="container py-5">

<!-- USER CARD TOP CENTER -->
<div class="text-center mb-4">

    <div style="
        max-width:400px;
        margin:auto;
        background: linear-gradient(135deg,#0f172a,#1e40af);
        border-radius: 20px;
        padding: 25px;
        color: white;
        box-shadow: 0 25px 60px rgba(0,0,0,0.3);
    ">

        <div style="font-size:60px;">🧑‍💻</div>

        <h3 style="color:#f97316; font-weight:800;">
            <?= htmlspecialchars($_SESSION['user_name']) ?> ✨
        </h3>

        <div style="font-size:12px; color:#cbd5f5;">
            <?= htmlspecialchars($_SESSION['user_role'] ?? 'User') ?>
        </div>

    </div>

</div>
    <div class="row g-4">

        

        <!-- BOOKINGS -->
        <div class="col-md-8 mx-auto">
            <div style="
                background:#ffffff;
                border-radius:20px;
                padding:25px;
                box-shadow:0 20px 60px rgba(0,0,0,0.1);
            ">

                <h4 style="font-weight:800; margin-bottom:20px;">
                    📅 My Bookings
                </h4>

                <?php if($result && $result->num_rows > 0): ?>

<?php
$groundBookings = [];
$coachBookings = [];

while($row = $result->fetch_assoc()){
    if(!empty($row['coach_id'])){
        $coachBookings[] = $row;
    } else {
        $groundBookings[] = $row;
    }
}
?>

<!-- ================= GROUND BOOKINGS ================= -->
<div style="margin-bottom:25px;">

<h5 style="
    font-weight:800;
    margin-bottom:15px;
    display:flex;
    align-items:center;
    gap:8px;
">
    🏟 <span>Ground Bookings</span>
</h5>

<?php if(count($groundBookings) > 0): ?>
    <?php foreach($groundBookings as $row): ?>

        <?php
        $time = $row['slot_time'];
        if(strpos($time, '-') !== false){
            list($start, $end) = explode('-', $time);
            $formatted_time = date('h:i A', strtotime(trim($start))) . ' - ' . date('h:i A', strtotime(trim($end)));
        } else {
            $formatted_time = $time;
        }
        ?>

        <div class="booking-card" id="card<?= $row['id'] ?>">

            <div>
                <div style="font-weight:700;">
                    <?= htmlspecialchars($row['ground_name']) ?>
                </div>
                <div style="font-size:12px; color:#64748b;">
                    <?= htmlspecialchars($row['sport_name']) ?>
                </div>
                <div>📅 <?= date('D, d M Y', strtotime($row['booking_date'])) ?></div>
                <div>🕒 <?= $formatted_time ?></div>
            </div>

            <div style="text-align:right;">
                <div style="color:#f97316;">₹<?= number_format($row['total_amount']) ?></div>

                <div style="margin-top:6px; display:flex; gap:8px; justify-content:flex-end; align-items:center;">

    <span class="status-badge">
        ✔ Confirmed
    </span>

    <?php if($row['status']=='confirmed'): ?>
    <button class="cancel-btn" onclick="cancelBooking(<?= $row['id'] ?>)">
        ✖ Cancel
    </button>
    <?php endif; ?>

</div>
            </div>

        </div>

    <?php endforeach; ?>
<?php else: ?>
</div>
    <div style="color:#64748b;">No ground bookings</div>
<?php endif; ?>


<!-- ================= COACH BOOKINGS ================= -->
<div style="margin-top:30px;">

<h5 style="
    font-weight:800;
    margin-bottom:15px;
    display:flex;
    align-items:center;
    gap:8px;
">
    🧑‍🏫 <span>Coaching Sessions</span>
</h5>

<?php if(count($coachBookings) > 0): ?>
    <?php foreach($coachBookings as $row): ?>

        <?php
        $time = $row['slot_time'];
        if(strpos($time, '-') !== false){
            list($start, $end) = explode('-', $time);
            $formatted_time = date('h:i A', strtotime(trim($start))) . ' - ' . date('h:i A', strtotime(trim($end)));
        } else {
            $formatted_time = $time;
        }
        ?>

        <div class="booking-card" id="card<?= $row['id'] ?>">

            <div>
                <div style="font-weight:700;">
                    <?= htmlspecialchars($row['coach_name']) ?>
                </div>
                <div style="font-size:12px; color:#64748b;">
                    <?= htmlspecialchars($row['sport_name']) ?>
                </div>
                <div>📅 <?= date('D, d M Y', strtotime($row['booking_date'])) ?></div>
                <div>🕒 <?= $formatted_time ?></div>
            </div>

            <div style="text-align:right;">
                <div style="color:#f97316;">₹<?= number_format($row['total_amount']) ?></div>

                <span style="background:#16a34a;color:white;padding:4px 10px;border-radius:20px;font-size:12px;">
                    <?= ucfirst($row['status']) ?>
                </span>

                <?php if($row['status']=='confirmed'): ?>
                <button class="cancel-btn mt-2" onclick="cancelBooking(<?= $row['id'] ?>)">
                    Cancel
                </button>
                <?php endif; ?>
            </div>

        </div>

    <?php endforeach; ?>
<?php else: ?>
</div>
    <div style="color:#64748b;">No coaching sessions</div>
<?php endif; ?>

<?php else: ?>
                    <div style="text-align:center; padding:40px; color:#64748b;">
                        😔 No bookings yet  
                    </div>

                <?php endif; ?>

            </div>
        </div>

    </div>
</div>

<script>
// ANIMATION LOAD
document.querySelectorAll('.booking-card').forEach((card, i) => {
    setTimeout(() => card.classList.add('show'), i * 100);
});

// CANCEL BOOKING
function cancelBooking(id){
    if(!confirm("Cancel this booking?")) return;

    fetch('actions/booking_action.php', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`action=cancel_booking&id=${id}`
    })
    .then(res => res.json())
    .then(res => {

        if(res.success){

            let card = document.getElementById('card'+id);

            card.style.opacity = '0.4';

            card.innerHTML += `
                <div style="color:red;font-size:12px;margin-top:5px;">
                    ❌ Cancelled
                </div>
            `;

            setTimeout(()=>{
                card.remove();
            },500);

        } else {
            alert(res.message || "Cancel failed");
        }

    })
    .catch(()=>{
        alert("Server error");
    });
}
</script>

<?php include 'includes/footer.php'; ?>