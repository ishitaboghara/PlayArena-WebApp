<?php 
session_start();
include 'includes/header.php';
include 'db.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ================= FIX START =================

// GET PARAMS
$ground_id = $_GET['id'] ?? null;
$coach_id  = $_GET['coach_id'] ?? null;

// IF ONLY COACH COMES → PICK ANY GROUND OF THAT SPORT
if(!$ground_id && $coach_id){

    $coachData = $conn->query("
        SELECT sport_id FROM coaches WHERE id = " . (int)$coach_id
    )->fetch_assoc();

    if(!$coachData){
        die("Invalid Coach");
    }

    $groundData = $conn->query("
    SELECT ground_id FROM coaches 
    WHERE id = " . (int)$coach_id
)->fetch_assoc();

if(!$groundData){
    die("No ground available for this coach");
}

$ground_id = $groundData['ground_id'];
}

// FINAL SAFETY
if(!$ground_id){
    die("Invalid Ground ID");
}

// ================= FIX END =================


// FETCH GROUND
$ground = $conn->query("
SELECT g.*, s.name as sport_name 
FROM grounds g 
JOIN sports s ON g.sport_id = s.id 
WHERE g.id = " . (int)$ground_id
)->fetch_assoc();

// FETCH COACHES OF SAME SPORT
$coaches = $conn->query("
    SELECT * FROM coaches 
    WHERE sport_id = " . (int)$ground['sport_id']
);
?>

<div class="container-fluid px-5 py-5">
    <div class="row g-5">

        <!-- LEFT -->
        <div class="col-lg-7">
            <div class="glass p-4">

                <img src="<?= !empty($ground['image_url']) ? $ground['image_url'] : 'https://images.unsplash.com/photo-1517649763962-0c623066013b' ?>" 
                     style="width:100%; height:260px; object-fit:cover; border-radius:14px;" class="mb-4">

                <h3 class="fw-bold"><?= $ground['name'] ?></h3>
                <p class="text-muted"><?= $ground['description'] ?? '' ?></p>

                <div class="d-flex gap-4 mt-3">
                    <span><b>Sport:</b> <?= $ground['sport_name'] ?></span>
                    <span><b>Location:</b> <?= $ground['location'] ?></span>
                </div>

                <div class="mt-3">
                    <span class="badge bg-success">₹<?= $ground['price_per_hour'] ?>/hr</span>
                </div>

            </div>
        </div>

        <!-- RIGHT -->
        <div class="col-lg-5">
            <div class="glass p-4">

                <h4 class="fw-bold mb-3">Book Slot</h4>

                <form id="bookingForm">

                    <input type="hidden" name="ground_id" value="<?= $ground['id'] ?>">
                    <input type="hidden" id="ground_price" value="<?= $ground['price_per_hour'] ?>">

                    <label>Date</label>
                    <input type="date" name="booking_date" class="form-input mb-3" required>

                    <label>Start Time</label>
                    <input type="time" id="start_time" class="form-input mb-2" required>

                    <label>End Time</label>
                    <input type="time" id="end_time" class="form-input mb-3" required>

                    <label>Coach</label>

                    <!-- ✅ AUTO SELECT COACH -->
                    <select id="coach_select" class="form-input mb-3">
                        <option value="" data-price="0">No Coach</option>

                        <?php 
                        while($c = $coaches->fetch_assoc()): 
                        ?>
                        <option 
                            value="<?= $c['id'] ?>" 
                            data-price="<?= $c['hourly_rate'] ?>"
                            <?= ($coach_id == $c['id']) ? 'selected' : '' ?>
                        >
                            <?= $c['name'] ?> (₹<?= $c['hourly_rate'] ?>/hr)
                        </option>
                        <?php endwhile; ?>
                    </select>

                    <div class="mb-3">
                        <b>Total: ₹<span id="display_total">0</span></b>
                    </div>

                    <button class="btn btn-premium w-100">
                        Proceed to Payment
                    </button>

                </form>

            </div>
        </div>

    </div>
</div>

<script>
function calculateTotal() {

    let price = Number(document.getElementById("ground_price").value) || 0;

    let start = document.getElementById("start_time").value;
    let end = document.getElementById("end_time").value;

    if(!start || !end){
    document.getElementById("display_total").innerText = "0";
    return;
}

    let startParts = start.split(":");
    let endParts = end.split(":");

    let startMinutes = parseInt(startParts[0]) * 60 + parseInt(startParts[1]);
    let endMinutes = parseInt(endParts[0]) * 60 + parseInt(endParts[1]);

    let hours = (endMinutes - startMinutes) / 60;

if(isNaN(hours)){
    document.getElementById("display_total").innerText = "0";
    return;
}

    if(hours <= 0 || hours > 24){
    document.getElementById("display_total").innerText = "0";
    return;
}

    let coach = document.getElementById("coach_select");
    let coachPrice = Number(coach.options[coach.selectedIndex].getAttribute("data-price")) || 0;

    let total = (price + coachPrice) * hours;

if(isNaN(total)){
    document.getElementById("display_total").innerText = "0";
    return;
}

    total = Math.ceil(total);

    let el = document.getElementById("display_total");
    el.innerHTML = "";

    setTimeout(() => {
        el.innerHTML = total.toLocaleString('en-IN');
    }, 0);

    window.currentTotal = total;
}

// EVENTS
document.getElementById("start_time").addEventListener("change", calculateTotal);
document.getElementById("end_time").addEventListener("change", calculateTotal);
document.getElementById("coach_select").addEventListener("change", calculateTotal);

// SUBMIT
document.getElementById("bookingForm").addEventListener("submit", function(e){
    e.preventDefault();

    let total = document.getElementById("display_total").innerText;

    if(!total || total === "Invalid" || total === "0"){
        alert("Please select valid time");
        return;
    }

    let formData = new FormData(this);
    let data = new URLSearchParams();

    for (let pair of formData.entries()) {
        data.append(pair[0], pair[1]);
    }

    data.append("start_time", document.getElementById("start_time").value);
    data.append("end_time", document.getElementById("end_time").value);
    data.append("coach_id", document.getElementById("coach_select").value);
    data.append("total_amount", parseInt(total.replace(/,/g,'')) || 0);

    sessionStorage.setItem("pending_booking", data.toString());

    window.location.href = "checkout.php?id=" + data.get("ground_id");
});
</script>

<?php include 'includes/footer.php'; ?>