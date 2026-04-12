<?php
include 'auth.php';
include '../db.php';
include '../includes/header.php';

$bookings = $conn->query("
SELECT b.*, g.name as ground, c.name as coach
FROM bookings b
LEFT JOIN grounds g ON b.ground_id = g.id
LEFT JOIN coaches c ON b.coach_id = c.id
ORDER BY b.id DESC
");
?><div class="container py-5"><!-- TITLE -->
<div class="text-center mb-5">
    <h2 class="fw-bold">📅 Manage Bookings</h2>
    <p class="text-muted">Track all ground & coaching bookings</p>
</div>

<!-- TABLE CARD -->
<div class="card shadow-lg border-0 rounded-4 p-4">

    <div class="table-responsive">

        <table class="table table-hover align-middle">

            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Ground</th>
                    <th>Coach</th>
                    <th>Date</th>
                    <th>Time Slot</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

            <?php if($bookings && $bookings->num_rows > 0): ?>
                <?php while($b = $bookings->fetch_assoc()): ?>

                <tr class="booking-row">

                    <td class="fw-bold"><?= $b['id'] ?></td>

                    <td>#<?= $b['user_id'] ?></td>

                    <td>
                        <?= $b['ground'] ?? '<span class="text-muted">—</span>' ?>
                    </td>

                    <td>
                        <?= $b['coach'] ?? '<span class="text-muted">—</span>' ?>
                    </td>

                    <td>
                        <?= date('d M Y', strtotime($b['booking_date'])) ?>
                    </td>

                    <td>
                        <span class="badge bg-info text-dark">
                            <?= $b['slot_time'] ?>
                        </span>
                    </td>

                    <td class="fw-bold text-success">
                        ₹<?= number_format($b['total_amount']) ?>
                    </td>

                    <td>
                        <?php if($b['status'] == 'confirmed'): ?>
                            <span class="badge bg-success">Confirmed</span>
                        <?php elseif($b['status'] == 'cancelled'): ?>
                            <span class="badge bg-danger">Cancelled</span>
                        <?php else: ?>
                            <span class="badge bg-secondary"><?= ucfirst($b['status']) ?></span>
                        <?php endif; ?>
                    </td>

                </tr>

                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        No bookings found.
                    </td>
                </tr>
            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</div><!-- PREMIUM STYLING --><style>
.booking-row{
    transition:0.25s;
}
.booking-row:hover{
    background:#f8f9fa;
    transform:scale(1.01);
}
.table th{
    font-size:13px;
    letter-spacing:0.5px;
}
.badge{
    font-size:12px;
    padding:6px 10px;
}
</style><?php include '../includes/footer.php'; ?>