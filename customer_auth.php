<?php
session_start();
require 'db.php';

$error = "";

// If already logged in as customer, go to account
if (isset($_SESSION['customer_id'])) {
    header("Location: my_account.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered. Please log in.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'customer')");
            if ($stmt->execute([$name, $email, $password])) {
                $_SESSION['customer_id'] = $pdo->lastInsertId();
                $_SESSION['customer_name'] = $name;
                header("Location: my_account.php");
                exit;
            }
        }
    } elseif (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'customer'");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['customer_id'] = $user['id'];
            $_SESSION['customer_name'] = $user['name'];
            header("Location: my_account.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Login | Bella Vista</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="logo.png">
    <style>
        .auth-container { max-width: 500px; margin: 80px auto; background: #F3E9DA; padding: 40px; border-radius: 15px; }
        .auth-form { background: #FBF7F0; padding: 30px; border-radius: 10px; border: 1px solid #D9BFA3; }
        .toggle-btn { background: transparent; border: none; color: #A9744F; text-decoration: underline; cursor: pointer; font-family: 'Jost', sans-serif; font-size: 14px; margin-top: 15px; display: block; text-align: center; width: 100%;}
    </style>
</head>
<body>
    <nav>
        <div class="logo">
            <a href="index.php" style="text-decoration:none; color:inherit; display:flex; align-items:center;">
                <img src="logo.png" alt="Logo" style="width: 40px; height: 40px; object-fit: contain; margin-right: 10px;"> 
                Bella Vista Oasis Spa
            </a>
        </div>
    </nav>

    <div class="auth-container">
        <h2 style="text-align: center; margin-bottom: 20px;">Customer Portal</h2>
        
        <?php if($error): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 14px; text-align: center;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <!-- LOGIN FORM -->
        <div id="login-form" class="auth-form">
            <h3 style="margin-bottom: 20px;">Log In</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn" style="width: 100%;">Log In</button>
            </form>
            <button class="toggle-btn" onclick="toggleForms()">Don't have an account? Register here.</button>
        </div>

        <!-- REGISTER FORM (Hidden by default) -->
        <div id="register-form" class="auth-form" style="display: none;">
            <h3 style="margin-bottom: 20px;">Create Account</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="text" name="mobile" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required minlength="6">
                </div>
                <button type="submit" name="register" class="btn" style="width: 100%;">Register</button>
            </form>
            <button class="toggle-btn" onclick="toggleForms()">Already have an account? Log in here.</button>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="guest_lookup.php" style="color: #6F4E37; font-size: 14px;">Guest? Find your booking here.</a>
        </div>
    </div>

    <script>
        function toggleForms() {
            let login = document.getElementById('login-form');
            let register = document.getElementById('register-form');
            if (login.style.display === 'none') {
                login.style.display = 'block';
                register.style.display = 'none';
            } else {
                login.style.display = 'none';
                register.style.display = 'block';
            }
        }
    </script>
</body>
</html>