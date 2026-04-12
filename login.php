<?php
include 'db.php';
session_start();

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){

        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // ✅ FIX (IMPORTANT)
            $_SESSION['user_role'] = $user['role'];

            header("Location: index.php");
            exit;

        } else {
            $error = "Wrong password";
        }

    } else {
        $error = "User not found";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="auth-wrapper">
    <div class="auth-container">

        <!-- LEFT -->
        <div class="auth-left auth-bg-login">
            <h1>Welcome Back 👋</h1>
            <p>Book grounds, join coaching and elevate your game.</p>
        </div>

        <!-- RIGHT -->
        <div class="auth-right">

            <div class="auth-card">

                <h2>Login <span>Now</span></h2>

                <?php if($error): ?>
                    <div class="auth-error"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">

                    <input type="email" name="email" placeholder="Email Address" required>

                    <input type="password" name="password" placeholder="Password" required>

                    <button class="btn-premium w-100">Login Securely</button>

                    <p>
                        Don’t have an account?
                        <a href="register.php">Register</a>
                    </p>

                </form>

            </div>

        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>