<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/index.css">
</head>

<body>
    <div class="login-bg">
        <div class="login-bg">
            <div class="login-card">
                <div class="login-title">LOGIN</div>
                <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
                <div style="flex:1; display:flex; flex-direction:column; justify-content:center;">
                    <form method="POST" class="login-form" autocomplete="off">
                        <input type="text" name="username" class="login-input" placeholder="Username" required>
                        <input type="password" name="password" class="login-input" placeholder="Password" required>
                        <button type="submit" class="login-btn">Login</button>
                    </form>
                </div>
                <div class="register-link">Belum punya akun? <a href="register.php">Registrasi</a></div>
            </div>
        </div>