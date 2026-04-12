console.log("MAIN JS LOADED");
$(document).ready(function() {

    // Auth Logic
    $('#loginForm').submit(function(e) {
        e.preventDefault();
        $.post('actions/auth_action.php', $(this).serialize() + '&action=login', function(res) {
            if(res.success) window.location.href = 'dashboard.php';
            else $('#authAlert').removeClass('d-none alert-success').addClass('alert-danger').text(res.message);
        });
    });

    $('#registerForm').submit(function(e) {
        e.preventDefault();
        $.post('actions/auth_action.php', $(this).serialize() + '&action=register', function(res) {
            if(res.success) {
                $('#authAlert').removeClass('d-none alert-danger').addClass('alert-success').text('Registration successful! Redirecting...');
                setTimeout(() => window.location.href = 'login.php', 2000);
            } else $('#authAlert').removeClass('d-none alert-success').addClass('alert-danger').text(res.message);
        });
    });

    

    // Checkout Logic (KEEP THIS)
    $(document).on('submit', '#checkoutForm', function(e) {
        e.preventDefault();

        console.log("PAY CLICKED");

        let pending = sessionStorage.getItem('pending_booking');

        if(!pending) {
            alert("Session expired");
            window.location.href = 'grounds.php';
            return;
        }

        $('#checkoutForm button').prop('disabled', true).text('Processing...');

        let data = pending + '&action=confirm_booking';

        $.ajax({
            url: 'actions/booking_action.php',
            type: 'POST',
            data: data,
            success: function(res) {

                if(typeof res === "string") {
                    try { res = JSON.parse(res); } catch(e) {
                        alert("Invalid server response");
                        return;
                    }
                }

                if(res.success) {
                    sessionStorage.removeItem('pending_booking');
                    alert('Booking Confirmed!');
                    window.location.href = 'dashboard.php';
                } else {
                    alert('Error: ' + (res.message || 'Something went wrong'));
                    $('#checkoutForm button').prop('disabled', false).text('Pay Now');
                }
            },
            error: function() {
                alert("AJAX failed");
                $('#checkoutForm button').prop('disabled', false).text('Pay Now');
            }
        });
    });

});
// Smooth scroll
document.querySelectorAll('a').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        if(this.hash !== ""){
            e.preventDefault();
            document.querySelector(this.hash)?.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});