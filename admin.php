<?php include 'includes/header.php'; 
if( !== 'admin') { header("Location: index.php"); exit; }

 = [
    'users' => ->query("SELECT COUNT(*) FROM users")->fetch_row()[0],
    'bookings' => ->query("SELECT COUNT(*) FROM bookings")->fetch_row()[0],
    'revenue' => ->query("SELECT SUM(total_amount) FROM bookings")->fetch_row()[0] ?? 0
];

 = ->query("SELECT b.*, u.name as user_name, g.name as ground_name FROM bookings b 
                                JOIN users u ON b.user_id = u.id 
                                JOIN grounds g ON b.ground_id = g.id 
                                ORDER BY b.created_at DESC LIMIT 5");
?>
<div class="container py-5">
    <div class="text-center mb-5 animate-fade">
        <h2 class="fw-bold fs-1">Admin <span class='text-primary'>Dashboard</span></h2>
        <p class="text-muted">Manage your sports arena empire</p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5 animate-fade">
        <div class="col-md-4">
            <div class="glass p-4 text-center">
                <h2 class="fw-bold text-primary mb-1"><?= ['users'] ?></h2>
                <p class="text-muted mb-0">Total Athletes</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass p-4 text-center">
                <h2 class="fw-bold text-primary mb-1"><?= ['bookings'] ?></h2>
                <p class="text-muted mb-0">Bookings Made</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass p-4 text-center">
                <h2 class="fw-bold text-accent mb-1">?<?= number_format(['revenue']) ?></h2>
                <p class="text-muted mb-0">Total Revenue</p>
            </div>
        </div>
    </div>

    <div class="row g-4 animate-fade" style="animation-delay: 0.1s">
        <div class="col-lg-8">
            <div class="glass p-4 h-100">
                <h4 class="fw-bold mb-4">Recent Transactions</h4>
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead class="text-muted small">
                            <tr>
                                <th>User</th>
                                <th>Ground</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while( = ->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars(['user_name']) ?></td>
                                <td><?= htmlspecialchars(['ground_name']) ?></td>
                                <td class="text-accent">?<?= number_format(['total_amount']) ?></td>
                                <td class="small text-muted"><?= date('d M, Y', strtotime(['booking_date'])) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="glass p-4 h-100">
                <h4 class="fw-bold mb-4">Quick Management</h4>
                <div class="d-grid gap-3">
                    <button class="btn btn-outline-primary rounded-pill py-3">Manage Grounds</button>
                    <button class="btn btn-outline-primary rounded-pill py-3">Manage Coaches</button>
                    <button class="btn btn-outline-primary rounded-pill py-3">Manage Users</button>
                    <button class="btn btn-outline-danger rounded-pill py-3">System Settings</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
