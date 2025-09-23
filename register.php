<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password_raw = $_POST['password'];
    // Username: min 8 chars, must contain letters and numbers
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\\d)[A-Za-z\\d]{8,}$/', $username)) {
        $error = "Username minimal 8 karakter dan campuran huruf & angka.";
    } else if (strlen($password_raw) < 8) {
        $error = "Password minimal 8 karakter.";
    } else {
        $password = password_hash($password_raw, PASSWORD_DEFAULT);
        $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Username sudah ada!";
        } else {
            mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username', '$password')");
            $_SESSION['register_success'] = true;
            header("Location: index.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/register.css">
</head>

<body>
    <div class="login-bg">
        <div class="login-card">
            <div class="login-title">REGISTER</div>
            <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
            <div style="flex:1; display:flex; flex-direction:column; justify-content:center;">
                <form method="POST" class="login-form" autocomplete="off">
                    <input type="text" name="username" class="login-input" placeholder="Username" required minlength="8" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" title="Minimal 8 karakter, campuran huruf dan angka">
                    <input type="password" name="password" class="login-input" placeholder="Password" required minlength="8" title="Minimal 8 karakter">
                    <button type="submit" class="login-btn">REGISTER</button>
                </form>
            </div>
            <div class="register-link">Sudah punya akun? <a href="index.php">LOGIN</a></div>
        </div>
    </div>
</body>

</html>