<?php
include 'auth.php';
include '../db.php';
include '../includes/header.php';

$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?><div class="container py-5"><!-- TITLE -->
<div class="text-center mb-5">
    <h2 class="fw-bold">👤 Manage Users</h2>
    <p class="text-muted">View all registered users</p>
</div>

<!-- CARD -->
<div class="card shadow-lg border-0 rounded-4 p-4">

    <div class="table-responsive">

        <table class="table align-middle table-hover">

            <thead class="table-light">
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                </tr>
            </thead>

            <tbody>

            <?php if($users && $users->num_rows > 0): ?>
                <?php while($u = $users->fetch_assoc()): ?>

                <tr class="user-row">

                    <!-- USER -->
                    <td>
                        <div class="d-flex align-items-center gap-3">

                            <img 
                                src="https://ui-avatars.com/api/?name=<?= urlencode($u['name']) ?>&background=ff6a2b&color=fff&size=64"
                                class="rounded-circle shadow-sm"
                                width="40" height="40"
                            >

                            <div>
                                <div class="fw-bold"><?= htmlspecialchars($u['name']) ?></div>
                                <div class="small text-muted">#<?= $u['id'] ?></div>
                            </div>

                        </div>
                    </td>

                    <!-- EMAIL -->
                    <td><?= htmlspecialchars($u['email']) ?></td>

                    <!-- ROLE -->
                    <td>
                        <?php if($u['role'] === 'admin'): ?>
                            <span class="badge bg-dark">Admin</span>
                        <?php else: ?>
                            <span class="badge bg-primary">User</span>
                        <?php endif; ?>
                    </td>

                    <!-- CREATED -->
                    <td>
                        <?= isset($u['created_at']) ? date('d M Y', strtotime($u['created_at'])) : '-' ?>
                    </td>

                </tr>

                <?php endwhile; ?>
            <?php else: ?>

                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        No users found.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</div><!-- PREMIUM STYLES --><style>
.user-row{
    transition:0.25s;
}
.user-row:hover{
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