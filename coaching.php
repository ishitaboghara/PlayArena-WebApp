<?php 
include 'includes/header.php'; 
include 'db.php';

// fetch coaches with sport
$result = $conn->query("
    SELECT c.*, s.name as sport_name 
    FROM coaches c
    JOIN sports s ON c.sport_id = s.id
");
?>

<div class="container-fluid px-5 py-5">

    <div class="text-center mb-5">
        <h2 class="fw-bold fs-1">
            Elite <span class="text-primary">Coaching</span> Programs
        </h2>
        <p class="text-muted">Train with professional coaches</p>
    </div>
    <div class="d-flex justify-content-center gap-3 mb-4">
    <span class="badge bg-dark">100+ Coaches</span>
    <span class="badge bg-success">Top Rated</span>
    <span class="badge bg-warning text-dark">Certified</span>
</div>

    <div class="row g-4">

    <?php while($row = $result->fetch_assoc()): ?>

    <div class="col-md-6 col-lg-4 col-xl-3">

        <div class="coach-card">

            <!-- IMAGE -->
            <div class="coach-img">
                <img src="<?= $row['image_url'] ?>" alt="coach">

                <span class="badge-sport">
                    <?= htmlspecialchars($row['sport_name']) ?>
                </span>

                <div class="price-tag">
                    ₹<?= number_format($row['hourly_rate']) ?>/hr
                </div>
            </div>

            <!-- INFO -->
            <div class="coach-info">

                <h5><?= htmlspecialchars($row['name']) ?></h5>

                <p class="text-muted small">
                    <?= htmlspecialchars($row['bio']) ?>
                </p>

                <div class="d-flex justify-content-between mb-2">

                    <span class="rating">
                        ⭐ <?= number_format((($row['id'] % 10)+40)/10,1) ?>
                    </span>
                    <?php if($row['experience_years'] > 15): ?>
    <div class="popular-badge">🔥 Top Coach</div>
<?php endif; ?>
                    <span class="status available">
                        <?= $row['experience_years'] ?> yrs exp
                    </span>

                </div>

                <a href="coach_booking.php?coach_id=<?= $row['id'] ?>" class="btn btn-premium w-100">
                   Enroll Now
                </a>

            </div>

        </div>

    </div>

    <?php endwhile; ?>

    </div>

</div>

<?php include 'includes/footer.php'; ?>