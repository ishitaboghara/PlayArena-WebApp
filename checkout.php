<?php 
include 'includes/header.php';
include 'db.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// extract values
$ground = $conn->query("
SELECT g.*, s.name as sport_name 
FROM grounds g 
JOIN sports s ON g.sport_id = s.id 
WHERE g.id = " . (int)($_GET['id'] ?? 1)
)->fetch_assoc();
?>

<div class="container py-5">
<div class="row g-5 justify-content-center">

    <!-- LEFT SUMMARY -->
    <div class="col-lg-5">
        <div class="glass p-4">

            <h5 class="fw-bold mb-3">Booking Summary</h5>

            <img src="<?= $ground['image_url'] ?>" 
                 style="width:100%; height:200px; object-fit:cover; border-radius:12px;" class="mb-3">

            <h6 class="fw-bold"><?= $ground['name'] ?></h6>
            <p class="text-muted small"><?= $ground['location'] ?></p>

            <hr>

            <div class="d-flex justify-content-between">
                <span>Date</span>
                <b><span id="summary-date"></span></b>
            </div>

            <div class="d-flex justify-content-between">
                <span>Time</span>
                <b><span id="summary-time"></span></b>
            </div>

            <div class="d-flex justify-content-between">
                <span>Coach</span>
                <b><span id="summary-coach"></span></b>
            </div>

            <hr>

            <div class="d-flex justify-content-between">
                <span>Total</span>
                <h5 class="fw-bold">₹<span id="summary-total"></span></h5>
            </div>

        </div>
    </div>

    <!-- RIGHT PAYMENT -->
    <div class="col-lg-5">
    <div class="checkout-card glass">

        <h4 class="checkout-title mb-4">Secure Checkout</h4>

        <!-- CARD VISUAL -->
        <div class="card-box">
            <div>**** **** **** 3456</div>
            <small>Secure Payment • SSL Protected</small>
        </div>

        <form id="checkoutForm" novalidate>

            <!-- NAME -->
            <label class="mb-1">Cardholder Name</label>
            <input type="text" id="name" class="checkout-input mb-3" placeholder="John Doe">

            <!-- CARD -->
            <label class="mb-1">Card Number</label>
            <input type="text" id="card" class="checkout-input mb-3" placeholder="1234 5678 9012 3456">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="mb-1">Expiry</label>
                    <input type="text" id="expiry" class="checkout-input" placeholder="MM/YY">
                </div>

                <div class="col-md-6">
                    <label class="mb-1">CVV</label>
                    <input type="password" id="cvv" class="checkout-input" placeholder="***">
                </div>
            </div>

            <button class="pay-btn w-100 mt-4">
                Pay ₹<span id="pay-btn-amount"></span>
            </button>

        </form>

    </div>
</div>
</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function(){

    // FORMATTERS
    document.getElementById("card").addEventListener("input", function(){
        this.value = this.value.replace(/\D/g,'')
            .replace(/(.{4})/g,'$1 ')
            .trim();
    });

    document.getElementById("expiry").addEventListener("input", function(){
        this.value = this.value.replace(/\D/g,'')
            .replace(/(\d{2})(\d+)/,'$1/$2');
    });

    // SUBMIT
    $(document).on('submit', '#checkoutForm', function(e){
        e.preventDefault();

        console.log("PAY CLICKED");

        let name = $('#name').val().trim();
        let card = $('#card').val().replace(/\s/g,'');
        let expiry = $('#expiry').val().trim();
        let cvv = $('#cvv').val().trim();

        if(name.length < 3){
            alert("Enter valid name");
            return;
        }

        if(!/^\d{16}$/.test(card)){
            alert("Card must be 16 digits");
            return;
        }

        if(!/^\d{2}\/\d{2}$/.test(expiry)){
            alert("Use MM/YY format");
            return;
        }

        if(!/^\d{3}$/.test(cvv)){
            alert("Invalid CVV");
            return;
        }

        let pending = sessionStorage.getItem('pending_booking');

        if(!pending){
            alert("Session expired");
            window.location.href = 'grounds.php';
            return;
        }

        $('#checkoutForm button').prop('disabled', true).text('Processing...');

        let params = new URLSearchParams(pending);

// 🔥 DETECT TYPE
let actionType = params.get("coach_id") 
    ? 'confirm_coach_booking' 
    : 'confirm_booking';

$.ajax({
    url: 'actions/booking_action.php',
    type: 'POST',
    data: pending + '&action=' + actionType,
    dataType: 'json',

    
    success: function(res){

    if(res.success){
        sessionStorage.removeItem('pending_booking');
        alert('Booking Confirmed!');
        window.location.href = 'dashboard.php';
    } else {
        alert(res.message || "Error");
        $('#checkoutForm button').prop('disabled', false).text('Pay Now');
    }
},
error: function(xhr){
    console.log(xhr.responseText);
    alert("AJAX failed - check console");
}
        });

    });

    // SUMMARY LOAD
let pending = sessionStorage.getItem("pending_booking");

if(!pending){
    alert("Session expired");
    window.location.href = "grounds.php";
    return;
}

let data = new URLSearchParams(pending);
let total = parseInt(data.get("total_amount") || 0);

if(total <= 0){
    alert("Invalid booking session");
    window.location.href = "grounds.php";
    return;
}

document.getElementById("summary-date").innerText = data.get("booking_date");
document.getElementById("summary-time").innerText =
    data.get("start_time") + " - " + data.get("end_time");

document.getElementById("summary-coach").innerText =
    data.get("coach_id") ? "With Coach" : "No Coach";

document.getElementById("summary-total").innerText =
    total.toLocaleString('en-IN');

document.getElementById("pay-btn-amount").innerText =
    total.toLocaleString('en-IN');

});
</script>

<?php include 'includes/footer.php'; ?>