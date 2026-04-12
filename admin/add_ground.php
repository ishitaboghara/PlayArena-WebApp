<?php
include 'auth.php';
include '../db.php';
include '../includes/header.php';
include __DIR__ . '/../includes/sports.php';
?><div class="container py-5"><div class="card shadow-lg border-0 rounded-4 p-4">

    <h3 class="fw-bold mb-4">🏟 Add Ground</h3>

    <!-- SUCCESS MESSAGE -->
    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">
            ✅ Ground added successfully!
        </div>
    <?php endif; ?>

    <!-- ERROR MESSAGE -->
    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            ❌ Something went wrong. Please fill all fields correctly.
        </div>
    <?php endif; ?>

    <form method="POST" action="actions/admin_action.php">

        <!-- NAME -->
        <input 
            name="name" 
            class="form-control mb-3 input-premium" 
            placeholder="Ground Name" 
            required
        >

        <!-- LOCATION -->
        <input 
            name="location" 
            class="form-control mb-3 input-premium" 
            placeholder="Location" 
            required
        >

        <!-- SPORT -->
        <select name="sport_id" class="form-control mb-3 input-premium" required>
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

        <!-- PRICE -->
        <input 
            name="price" 
            type="number" 
            class="form-control mb-3 input-premium" 
            placeholder="Price per hour (₹)" 
            required
        >

        <!-- IMAGE -->
        <input 
            name="image" 
            class="form-control mb-3 input-premium" 
            placeholder="Image URL (optional)"
        >

        <input type="hidden" name="action" value="add_ground">

        <!-- BUTTON -->
        <button class="btn btn-premium w-100 py-2 fw-bold">
            ➕ Add Ground
        </button>

    </form>

</div>

</div><!-- PREMIUM STYLES --><style>
.input-premium{
    transition:0.3s;
}
.input-premium:focus{
    border-color:#ff6a2b;
    box-shadow:0 0 10px rgba(255,106,43,0.4);
}

.btn-premium{
    background:linear-gradient(135deg,#ff6a2b,#ff9a3c);
    border:none;
    color:white;
    transition:0.3s;
}
.btn-premium:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(255,106,43,0.4);
}
</style><?php include '../includes/footer.php'; ?>