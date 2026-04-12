<?php
session_start();
require '../db.php';

$error = '';
$success = '';

/* ===== LOGIN ===== */
if(isset($_POST['login']) && !empty($_POST['email']) && !empty($_POST['password'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $res = $conn->query("SELECT * FROM users WHERE email='$email' AND role='admin'");

    if($res->num_rows > 0){
        $admin = $res->fetch_assoc();

        if(password_verify($password, $admin['password'])){
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['user_name'] = $admin['name'];
            $_SESSION['user_role'] = 'admin';

            header("Location: dashboard.php");
            exit;
        }
    }

    $error = "Invalid login!";
}

/* ===== REGISTER ===== */
if(isset($_POST['register'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // CHECK IF EMAIL EXISTS
$check = $conn->query("SELECT id FROM users WHERE email='$email'");

if($check->num_rows > 0){
    $error = "Admin already exists with this email!";
} else {

    $conn->query("
        INSERT INTO users (name,email,password,role)
        VALUES ('$name','$email','$password','admin')
    ");

    $success = "Admin registered! Now login.";
}
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Admin Access</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* BACKGROUND */
body{
    margin:0;
    height:100vh;
    background:url('https://images.unsplash.com/photo-1517649763962-0c623066013b') no-repeat center/cover;
    display:flex;
    align-items:center;
    justify-content:center;
}

/* DARK OVERLAY */
body::before{
    content:"";
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.6);
}

/* CARD */
.auth-card{
    position:relative;
    z-index:2;
    width:350px;
    padding:30px;
    border-radius:16px;
    backdrop-filter: blur(15px);
    background:rgba(255,255,255,0.1);
    color:white;
    box-shadow:0 10px 40px rgba(0,0,0,0.4);
    transition:0.4;
}
.auth-card:hover{
    transform:scale(1.02);
    box-shadow:0 20px 50px rgba(0,0,0,0.5);
}

.form-control:hover{
    background:rgba(255,255,255,0.3);
}
/* INPUT */
.form-control{
    background:rgba(255,255,255,0.2);
    border:1px solid transparent;
    color:white;
    transition:0.3s;
}
.form-control::placeholder{
    color:#ddd;
     transition:0.3s;
}
.form-control:focus::placeholder{
    opacity:0.5;
}
.form-control:focus{
    background:rgba(255,255,255,0.4);
    border:1px solid #ffb347;
    box-shadow:0 0 10px rgba(255,183,71,0.5);
    outline:none;
}

/* BUTTON */
.btn-premium{
    background:linear-gradient(135deg,#ff7a18,#ffb347);
    border:none;
    color:white;
    transition:0.3;
}
.btn-premium:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(255,122,24,0.4);
}

/* TOGGLE */
.toggle{
    cursor:pointer;
    color:#ffb347;
}

</style>
</head>

<body>

<div class="auth-card">

    <h4 class="text-center mb-3">⚙️ Admin Access</h4>

    <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <!-- LOGIN -->
    <form method="POST" id="loginForm">
        <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
        <button name="login" class="btn btn-premium w-100">Login</button>
        <p class="mt-3 text-center">
            New admin? <span class="toggle" onclick="showRegister()">Register</span>
        </p>
    </form>

    <!-- REGISTER -->
    <form method="POST" id="registerForm" style="display:none;">
        <input type="text" name="name" class="form-control mb-3" placeholder="Full Name" required>
        <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
        <button name="register" class="btn btn-premium w-100">Register</button>
        <p class="mt-3 text-center">
            Already admin? <span class="toggle" onclick="showLogin()">Login</span>
        </p>
    </form>

</div>

<script>
function showRegister(){
    document.getElementById('loginForm').style.display='none';
    document.getElementById('registerForm').style.display='block';
}
function showLogin(){
    document.getElementById('loginForm').style.display='block';
    document.getElementById('registerForm').style.display='none';
}
</script>

</body>
</html>