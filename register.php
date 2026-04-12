<?php
include 'db.php';

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if($password !== $confirm){
        $error = "Passwords do not match";
    } else {

        $check = $conn->prepare("SELECT id FROM users WHERE email=?");
        $check->bind_param("s",$email);
        $check->execute();
        $res = $check->get_result();

        if($res->num_rows > 0){
            $error = "Email already exists";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,'user')");
            $stmt->bind_param("sss",$name,$email,$hash);

            if($stmt->execute()){
                header("Location: login.php");
                exit;
            }
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="auth-wrapper">
    <div class="auth-container">

        <!-- LEFT -->
        <div class="auth-left auth-bg-register">
            <h1>Join PlayArena 🚀</h1>
            <p>Create your account and start booking instantly.</p>

        </div>

        <!-- RIGHT -->
        <div class="auth-right">

            <div class="auth-card">

                <h2>Create <span>Account</span></h2>

                <?php if($error): ?>
                    <div class="auth-error"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">

                    <input type="text" name="name" placeholder="Full Name" required>

                    <input type="email" name="email" placeholder="Email Address" required>

                    <input type="password" name="password" placeholder="Password" required>

                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>

                    <button class="btn-premium w-100">Create Account</button>

                    <p>
                        Already have an account?
                        <a href="login.php">Login</a>
                    </p>

                </form>

            </div>

        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>