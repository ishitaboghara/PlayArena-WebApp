<?php
session_start();
include 'includes/header.php';
require_once 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$coach_id = (int)($_GET['coach_id'] ?? 0);

if(!$coach_id){
    die("Invalid Coach");
}

$coach = $conn->query("
    SELECT c.*, s.name as sport_name 
    FROM coaches c
    JOIN sports s ON c.sport_id = s.id
    WHERE c.id = $coach_id
")->fetch_assoc();

if(!$coach){
    die("Coach not found");
}
?>

<div class="container py-5">
    <div class="row g-5">

        <!-- LEFT -->
        <div class="col-md-6">
            <div class="glass p-4 text-center">

                <img src="<?= $coach['image_url'] ?>" 
                     class="img-fluid rounded mb-3" 
                     style="height:220px; object-fit:cover;">

                <h3 class="fw-bold"><?= $coach['name'] ?></h3>
                <p class="text-muted"><?= $coach['sport_name'] ?></p>

                <div class="mb-2">⭐ <?= number_format((($coach['id']%10)+40)/10,1) ?></div>
                <div class="mb-2"><?= $coach['experience_years'] ?> yrs experience</div>

                <span class="badge bg-success">
                    ₹<?= $coach['hourly_rate'] ?>/hr
                </span>

            </div>
        </div>

        <!-- RIGHT -->
        <div class="col-md-6">
            <div class="glass p-4">

                <h4 class="fw-bold mb-4">Book Coaching Session</h4>

                <form id="coachForm">

                    <input type="hidden" name="coach_id" value="<?= $coach_id ?>">
                    <input type="hidden" id="rate" value="<?= $coach['hourly_rate'] ?>">

                    <!-- PLAN TYPE -->
                    <label>Plan Type</label>
                    <select id="planType" class="form-input mb-3">
                        <option value="single">Single Session</option>
                        <option value="monthly">Monthly Plan (30 Hours)</option>
                    </select>

                    <!-- DATE -->
                    <label>Date</label>
                    <input type="date" name="booking_date" class="form-input mb-3" required>

                    <!-- TIME SLOT -->
                    <label>Start Time</label>
                    <input type="time" id="start_time" class="form-input mb-2" required>

                    <label>Duration</label>
                    <select id="duration" class="form-input mb-3">
                        <option value="1">1 Hour</option>
                        <option value="2">2 Hours</option>
                        <option value="3">3 Hours</option>
                        <option value="4">4 Hours</option>
                    </select>

                    <!-- MONTHLY INFO -->
                    <div id="monthlyBox" style="display:none;" class="mb-3">
                        <div style="font-size:13px;color:#64748b;">
                            ✔ 30 Hours Package <br>
                            ✔ Flexible scheduling <br>
                            ✔ Valid for 30 days
                        </div>
                    </div>

                    <!-- TOTAL -->
                    <div class="mb-3">
                        <b>Total: ₹<span id="total">0</span></b>
                    </div>

                    <button class="btn btn-premium w-100">
                        Confirm Booking
                    </button>

                </form>

            </div>
        </div>

    </div>
</div>

<script>

// 🔥 UPDATE UI
function updateUI(){
    let type = document.getElementById('planType').value;

    if(type === 'monthly'){
        document.getElementById('monthlyBox').style.display = 'block';
        document.getElementById('duration').disabled = true;
    } else {
        document.getElementById('monthlyBox').style.display = 'none';
        document.getElementById('duration').disabled = false;
    }

    updateTotal();
}

// 💰 TOTAL CALCULATION
function updateTotal(){
    let rate = Number(document.getElementById('rate').value);
    let type = document.getElementById('planType').value;

    let hours = (type === 'monthly') ? 30 : Number(document.getElementById('duration').value);

    let total = rate * hours;

    document.getElementById('total').innerText = total.toLocaleString('en-IN');
    window.totalAmount = total;
}

// EVENTS
document.getElementById('planType').addEventListener('change', updateUI);
document.getElementById('duration').addEventListener('change', updateTotal);

updateUI();

// 🚀 SUBMIT
document.getElementById('coachForm').addEventListener('submit', function(e){
    e.preventDefault();

    let type = document.getElementById('planType').value;
    let start = document.getElementById('start_time').value;

    if(!start){
        alert("Please select time");
        return;
    }

    let duration = (type === 'monthly') ? 30 : document.getElementById('duration').value;

    let end = new Date(`1970-01-01T${start}`);
    end.setHours(end.getHours() + parseInt(duration));

    let endTime = end.toTimeString().slice(0,5);

    let params = new URLSearchParams({
        coach_id: <?= $coach_id ?>,
        booking_date: this.booking_date.value,
        start_time: start,
        end_time: endTime,
        total_amount: window.totalAmount,
        plan_type: type
    });

    // 🔥 SAVE DATA
    sessionStorage.setItem("pending_booking", params.toString());

    // 🔥 REDIRECT
    window.location.href = "checkout.php";
});
</script>

<?php include 'includes/footer.php'; ?>