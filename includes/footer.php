<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$user_role = $_SESSION['user_role'] ?? 'user';
?><?php if($user_role !== 'admin'): ?><!-- ================= USER FOOTER ================= --><footer class="mt-5 pt-5 pb-4 bg-white border-top"><div class="container">

    <div class="row g-4">

        <!-- BRAND -->
        <div class="col-md-4">
            <h4 class="fw-bold mb-3">
                <span style="color:#ff6a2b;">Play</span>Arena
            </h4>
            <p class="text-muted">
                Mumbai’s premium sports booking platform. Book grounds, join coaching,
                and elevate your game.
            </p>

            <div class="d-flex gap-3 mt-3">
                <i class="bi bi-facebook fs-5"></i>
                <i class="bi bi-instagram fs-5"></i>
                <i class="bi bi-twitter fs-5"></i>
            </div>
        </div>

        <!-- LINKS -->
        <div class="col-md-4">
            <h5 class="fw-bold mb-3">Explore</h5>
            <ul class="list-unstyled">

                <li><a href="/PlayArena_web/index.php" class="text-decoration-none text-muted d-block mb-2">Home</a></li>
                <li><a href="/PlayArena_web/grounds.php" class="text-decoration-none text-muted d-block mb-2">Grounds</a></li>
                <li><a href="/PlayArena_web/coaching.php" class="text-decoration-none text-muted d-block mb-2">Coaching</a></li>
                <li><a href="/PlayArena_web/sports.php" class="text-decoration-none text-muted d-block mb-2">Sports</a></li>
                <li><a href="/PlayArena_web/about.php" class="text-decoration-none text-muted d-block mb-2">About Club</a></li>

            </ul>
        </div>

        <!-- CONTACT -->
        <div class="col-md-4">
            <h5 class="fw-bold mb-3">Contact</h5>

            <p class="text-muted mb-2">
                <i class="bi bi-geo-alt me-2"></i> Andheri West, Mumbai
            </p>

            <p class="text-muted mb-2">
                <i class="bi bi-envelope me-2"></i> support@playarena.com
            </p>

            <p class="text-muted">
                <i class="bi bi-phone me-2"></i> +91 98765 43210
            </p>

        </div>

    </div>

    <hr class="my-4">

    <div class="d-flex justify-content-between small text-muted">
        <span>© <?= date('Y') ?> PlayArena</span>
        <span>Built with ❤️ for sports lovers</span>
    </div>

</div>

</footer><?php else: ?><!-- ================= ADMIN FOOTER ================= --><footer class="mt-5 pt-4 pb-3 bg-white border-top"><div class="container text-center">

    <span class="fw-semibold">
        ⚙️ Admin Panel - <span style="color:#ff6a2b;">Play</span>Arena
    </span>

    <div class="small text-muted mt-1">
        Manage system efficiently | © <?= date('Y') ?>
    </div>

</div>

</footer><?php endif; ?><!-- JS --><script src="https://code.jquery.com/jquery-3.6.0.min.js"></script><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script><script src="/PlayArena_web/assets/js/main.js"></script></body>
</html>