<?php 
include 'includes/header.php'; 
include 'db.php';

// Fix variables
$sport_id = $_GET['sport_id'] ?? '';
$location = $_GET['location'] ?? '';
$sort = $_GET['sort'] ?? '';
$availability = $_GET['availability'] ?? '';
$rating = $_GET['rating'] ?? '';
$conditions = [];

if(!empty($sport_id)) {
    $conditions[] = "g.sport_id = " . (int)$sport_id;
}

if(!empty($location)) {
    $conditions[] = "g.location LIKE '%" . $conn->real_escape_string($location) . "%'";
}


// FORCE SHOW ALL GROUNDS (FIX)
$where = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
$order = "";

if($sort == "price_low") {
    $order = "ORDER BY g.price_per_hour ASC";
} elseif($sort == "price_high") {
    $order = "ORDER BY g.price_per_hour DESC";
}
// Fix query
$result = $conn->query("
    SELECT g.*, s.name as sport_name 
    FROM grounds g 
    JOIN sports s ON g.sport_id = s.id
    $where 
    $order
");
?>
<div class="container-fluid px-5 py-5">

    <h5 class="mb-3">Total Grounds: <?= $result->num_rows ?></h5>

    <div class="text-center mb-5 animate-fade">
        <h2 class="fw-bold fs-1">Find Your <span class='text-primary'>Arena</span></h2>
        <p class="text-muted">Premium sports grounds across Mumbai</p>
    </div>

    <!-- Filter Bar -->
    <div class="glass p-4 mb-5">
    <form method="GET" class="row g-3 align-items-center justify-content-center">

        <!-- SPORT -->
        <div class="col-md-2">
            <select name="sport_id" class="form-input">
                <option value="">All Sports</option>
                <?php
                $sports = $conn->query("SELECT * FROM sports");
                while($s = $sports->fetch_assoc()) {
                    $selected = ($sport_id == $s['id']) ? 'selected' : '';
                    echo "<option value='{$s['id']}' $selected>{$s['name']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- LOCATION -->
        <div class="col-md-2">
            <input type="text" name="location" class="form-input"
                placeholder="Location"
                value="<?= htmlspecialchars($location) ?>">
        </div>

        <!-- SORT -->
        <div class="col-md-2">
            <select name="sort" class="form-input">
                <option value="">Sort By</option>
                <option value="price_low" <?= $sort=='price_low'?'selected':'' ?>>Price Low → High</option>
                <option value="price_high" <?= $sort=='price_high'?'selected':'' ?>>Price High → Low</option>
            </select>
        </div>

        <!-- AVAILABILITY -->
        <div class="col-md-2">
            <select name="availability" class="form-input">
                <option value="">Availability</option>
                <option value="available" <?= $availability=='available'?'selected':'' ?>>Available Only</option>
            </select>
        </div>

        <!-- RATING -->
        <div class="col-md-2">
            <select name="rating" class="form-input">
                <option value="">Rating</option>
                <option value="4" <?= $rating=='4'?'selected':'' ?>>4+ Stars</option>
                <option value="3" <?= $rating=='3'?'selected':'' ?>>3+ Stars</option>
            </select>
        </div>

        <!-- BUTTON -->
        <div class="col-md-2">
            <button class="btn btn-premium w-100">Apply</button>
        </div>
        <a href="grounds.php" class="btn btn-secondary">Reset</a>

    </form>
</div>
<?php
if(!$result){
    die("Query Error: " . $conn->error);
}
?>
    <!-- Grounds Grid -->
    <div class="row g-4 justify-content-start">

<?php if($result && $result->num_rows > 0): ?>

<?php while($row = $result->fetch_assoc()): ?>

<?php
// ===== FIXED LOGIC (TOP ONLY ONCE PER CARD) =====

// rating
$displayRating = number_format((($row['id'] % 10) + 40) / 10, 1);

// availability
$available = ($row['id'] % 2 == 0);

// popular
$popular = ($row['id'] % 3 == 0);

// images
$images = [
    "Cricket" => [
        "https://images.unsplash.com/photo-1674986778924-7a33c1531443?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Nnx8Q1JJQ0tFVCUyMFRVUkYlMjBBTkQlMjBHUk9VTkRTfGVufDB8fDB8fHww",
        "https://images.unsplash.com/photo-1761757106344-441482b56693?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8Q1JJQ0tFVCUyMFRVUkZ8ZW58MHx8MHx8fDA%3D",
        "https://images.unsplash.com/photo-1624193634221-33b652971323?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTV8fHR1cmYlMjBDUklDS0VUfGVufDB8fDB8fHww",
   
        ],
    "Football" => [
        "https://plus.unsplash.com/premium_photo-1671489203034-fc619a2de3bf?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8Rk9PVEJBTEwlMjBDT1VSVHxlbnwwfHwwfHx8MA%3D%3D",
        "https://plus.unsplash.com/premium_photo-1663948061665-34c2b6d42381?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTN8fEZPT1RCQUxMJTIwQ09VUlR8ZW58MHx8MHx8fDA%3D",
        "https://plus.unsplash.com/premium_photo-1685089027812-6885c06b0fbf?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTd8fEZPT1RCQUxMJTIwQ09VUlR8ZW58MHx8MHx8fDA%3D",
    ],
    "Badminton" => [
        "https://images.unsplash.com/photo-1723633236252-eb7badabb34c?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTB8fEJBRE1JTlRPTiUyMEdST1VORFMlMjBSRUFMJTIwSU1BR0VTfGVufDB8fDB8fHww",
        "https://images.unsplash.com/photo-1599391398131-cd12dfc6c24e?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTR8fEJBRE1JTlRPTiUyMENPVVJUfGVufDB8fDB8fHww",
        "https://media.istockphoto.com/id/1475459188/photo/sports-workout-and-male-tennis-player-playing-match-at-an-outdoor-court-stadium-for-training.jpg?s=612x612&w=is&k=20&c=y1d8oHQTRd93roX1S4nlRbom2IDLZ-8WUNzEq9l-jp8=",
    ],
    "Tennis" => [
        "https://images.unsplash.com/flagged/photo-1576972405668-2d020a01cbfa?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTB8fFRFTk5JUyUyMENPVVJUfGVufDB8fDB8fHww",
        "https://plus.unsplash.com/premium_photo-1663039984787-b11d7240f592?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTN8fFRFTk5JUyUyMENPVVJUfGVufDB8fDB8fHww",
        "https://plus.unsplash.com/premium_photo-1664303119944-4cf5302bb701?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8VEVOTklTJTIwQ09VUlR8ZW58MHx8MHx8fDA%3D",
        ],
    "Basketball" => [
        "https://images.unsplash.com/photo-1600534220378-df36338afc40?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Nnx8QkFTS0VUQkFMTCUyMENPVVJUfGVufDB8fDB8fHww",
        "https://plus.unsplash.com/premium_photo-1675364966937-c2bdf5bce9b5?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8QkFTS0VUQkFMTCUyMENPVVJUfGVufDB8fDB8fHww",
        "https://images.unsplash.com/photo-1590227632180-80a3bf110871?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fEJBU0tFVEJBTEwlMjBDT1VSVHxlbnwwfHwwfHx8MA%3D%3D",
    ],
    "Volleyball" => [
        "https://plus.unsplash.com/premium_photo-1708696216242-a432e73ecd72?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8Vk9MTFlCQUxMTCUyMENPVVJUfGVufDB8fDB8fHww",
        "https://plus.unsplash.com/premium_photo-1708696216310-5abfafa9aec9?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTN8fFZPTExZQkFMTEwlMjBDT1VSVHxlbnwwfHwwfHx8MA%3D%3D",
        "https://images.unsplash.com/photo-1503152977911-f125b5741a6d?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTl8fFZPTExZQkFMTEwlMjBDT1VSVHxlbnwwfHwwfHx8MA%3D%3D",
    ],
    "Table Tennis" => [
        "https://images.unsplash.com/photo-1708268418738-4863baa9cf72?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8VEFCTEUlMjBUQU5OSVMlMjBTUE9SVHxlbnwwfHwwfHx8MA%3D%3D",
        "https://images.unsplash.com/photo-1746052379113-a2ab829e161b?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8N3x8VEFCTEUlMjBUQU5OSVMlMjBTUE9SVHxlbnwwfHwwfHx8MA%3D%3D",
        "https://images.unsplash.com/photo-1617473515500-05a0b4a2306e?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fFRBQkxFJTIwVEFOTklTJTIwU1BPUlR8ZW58MHx8MHx8fDA%3D",

    ],
    "Swimming" => [
        "https://plus.unsplash.com/premium_photo-1663040082818-b25debfd997f?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8U1dJTU1JTkclMjBQT09MJTIwQ09BQ0hJTkd8ZW58MHx8MHx8fDA%3D",
        "https://images.unsplash.com/photo-1652911367218-36fef4d8e60d?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OHx8U1dJTU1JTkclMjBQT09MJTIwQ09BQ0hJTkd8ZW58MHx8MHx8fDA%3D",
        ],
];

$defaultImages = [
    "https://images.unsplash.com/photo-1517649763962-0c623066013b"
];

// stable image
if(isset($images[$row['sport_name']])) {
    $imgList = $images[$row['sport_name']];
    $img = $imgList[$row['id'] % count($imgList)];
} else {
    $img = $defaultImages[0];
}
?>

<div class="col-md-6 col-lg-4 col-xl-3 mb-4">

<div class="ground-card">

    <div class="ground-img">
        <img src="<?= $row['image_url'] ?>">

        <span class="badge-sport"><?= htmlspecialchars($row['sport_name']) ?></span>

        <div class="price-tag">
            ₹<?= number_format($row['price_per_hour']) ?>/hr
        </div>
    </div>

    <div class="ground-info">

        <div class="d-flex justify-content-between mb-2">
            <span class="rating">⭐ <?= $displayRating ?></span>

            <span class="status <?= $available ? 'available' : 'full' ?>">
                <?= $available ? 'Available' : 'Full' ?>
            </span>
        </div>

        <?php if($popular): ?>
            <div class="popular-badge">🔥 Popular</div>
        <?php endif; ?>

        <h4><?= htmlspecialchars($row['name']) ?></h4>

        <p class="location">📍 <?= htmlspecialchars($row['location']) ?></p>

        <a href="booking.php?id=<?= $row['id'] ?>" class="btn btn-premium w-100">
            Book Now
        </a>

    </div>

</div>

</div>

<?php endwhile; ?>

<?php else: ?>
    <p>No grounds found</p>
<?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>