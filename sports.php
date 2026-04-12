<style>
body{
    background: #f9efe7;
}
</style>

<?php include 'includes/header.php'; ?>

<section class="py-5" style="background:#f8fafc;">
    <div class="container">

        <div class="text-center mb-5">
            <h1 class="fw-bold">Our <span style="color:#f97316;">Sports</span></h1>
            <p class="text-muted">Explore all sports facilities available at PlayArena</p>
        </div>

        <div class="row g-4">

<?php
$sports = $conn->query("SELECT * FROM sports");

while($sport = $sports->fetch_assoc()):

$name = strtolower($sport['name']);

if($name == "cricket") {
    $img = "https://images.unsplash.com/photo-1593341646782-e0b495cff86d";
    $desc = "Premium cricket pitches with professional facilities.";
}
elseif($name == "football") {
    $img = "https://images.unsplash.com/photo-1551958219-acbc608c6377";
    $desc = "High-quality football turfs with lighting.";
}
elseif($name == "badminton") {
    $img = "https://images.unsplash.com/photo-1587280501635-68a0e82cd5ff";
    $desc = "Indoor courts with pro flooring.";
}
elseif($name == "tennis") {
    $img = "https://images.unsplash.com/photo-1542144582-1ba00456b5e3";
    $desc = "Top-class courts for all players.";
}
elseif($name == "basketball") {
    $img = "https://images.unsplash.com/photo-1518063319789-7217e6706b04";
    $desc = "Modern courts with pro hoops.";
}
elseif($name == "volleyball") {
    $img = "https://images.unsplash.com/photo-1592656094267-764a45160876";
    $desc = "Indoor & sand volleyball courts.";
}
elseif($name == "table tennis") {
    $img = "https://images.unsplash.com/photo-1609710228159-0fa9bd7c0827";
    $desc = "Professional TT tables & setup.";
}
elseif($name == "swimming") {
    $img = "https://images.unsplash.com/photo-1576610616656-d3aa5d1f4534?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fHN3aW1taW5nJTIwcG9vbHxlbnwwfHwwfHx8MA%3D%3D";
    $desc = "Clean pools with trained staff.";
}
?>

<div class="col-md-6 col-lg-3">

<div class="sport-card-new">

    <img src="<?= $img ?>">

    <div class="info">
        <h4><?= $sport['name'] ?></h4>
        <p><?= $desc ?></p>

        <a href="grounds.php?sport_id=<?= $sport['id'] ?>">
            Explore Grounds→
        </a>
    </div>

</div>

</div>

<?php endwhile; ?>

</div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>