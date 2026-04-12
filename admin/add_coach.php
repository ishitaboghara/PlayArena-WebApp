<?php
include 'auth.php';
include '../db.php';
include '../includes/header.php';
include __DIR__ . '/../includes/sports.php';

$grounds = $conn->query("SELECT * FROM grounds");
?><div class="container py-5"><div class="card shadow-lg border-0 rounded-4 p-4">

    <h3 class="fw-bold mb-4">🏋️ Add Coach</h3>

    <!-- SUCCESS -->
    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">
            ✅ Coach added successfully!
        </div>
    <?php endif; ?>

    <!-- ERROR -->
    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            ❌ Please fill required fields correctly.
        </div>
    <?php endif; ?>

    <form method="POST" action="actions/admin_action.php">

        <!-- NAME -->
        <input 
            name="name" 
            class="form-control mb-3 input-premium" 
            placeholder="Coach Name" 
            required
        >

        <!-- SPORT -->
        <select 
            name="sport_id" 
            id="sportSelect" 
            class="form-control mb-3 input-premium" 
            required
        >
            <option value="">Select Sport</option>
            <option value="1">Cricket</option>
<option value="2">Football</option>
<option value="3">Badminton</option>
<option value="4">Tennis</option>
<option value="5">Basketball</option>
<option value="6">Volleyball</option>
<option value="7">Table Tennis</option>
<option value="8">Swimming</option>
        </select>

        <!-- EXPERIENCE -->
        <input 
            name="experience" 
            type="number" 
            class="form-control mb-3 input-premium" 
            placeholder="Experience (years)"
        >

        <!-- PRICE -->
        <input 
            name="price" 
            type="number" 
            class="form-control mb-3 input-premium" 
            placeholder="Price per hour (₹)"
        >

        <!-- IMAGE -->
        <input 
            name="image" 
            class="form-control mb-3 input-premium" 
            placeholder="Coach Image URL (optional)"
        >

        <!-- GROUND SELECT -->
        <select 
            name="ground_id" 
            id="groundSelect" 
            class="form-control mb-3 input-premium"
        >
            <option value="">Select Ground</option>

            <?php while($g=$grounds->fetch_assoc()): ?>
                <option 
                    value="<?= $g['id'] ?>" 
                    data-sport="<?= $g['sport_id'] ?>"
                >
                    <?= $g['name'] ?> (<?= $g['sport_id'] ?>)
                </option>
            <?php endwhile; ?>

        </select>

        <!-- BIO -->
        <textarea 
            name="bio" 
            class="form-control mb-3 input-premium" 
            placeholder="Coach Bio"
        ></textarea>

        <input type="hidden" name="action" value="add_coach">

        <!-- BUTTON -->
        <button class="btn btn-success w-100 py-2 fw-bold">
            ➕ Add Coach
        </button>

    </form>

</div>

</div><!-- SMART FILTER SCRIPT --><script>
const sportDropdown = document.getElementById('sportSelect');
const groundDropdown = document.getElementById('groundSelect');

sportDropdown.addEventListener('change', function(){
    let selectedSport = this.value;

    Array.from(groundDropdown.options).forEach(option => {
        if(option.value === "") return;

        if(option.getAttribute('data-sport') === selectedSport){
            option.style.display = 'block';
        } else {
            option.style.display = 'none';
        }
    });

    groundDropdown.value = "";
});
</script><!-- PREMIUM STYLING --><style>
.input-premium{
    transition:0.3s;
}
.input-premium:focus{
    border-color:#28a745;
    box-shadow:0 0 10px rgba(40,167,69,0.4);
}

.btn-success{
    transition:0.3s;
}
.btn-success:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(40,167,69,0.4);
}
</style><?php include '../includes/footer.php'; ?>