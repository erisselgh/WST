<?php
session_start();
require 'db.php';

$error = "";

// If already logged in, redirect to admin
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user from database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verify password (Assuming you are using password_hash in your DB)
    // If you are using plain text for testing, change this to: if ($user && $password === $user['password'])
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = $user['name'];
        header("Location: admin.php");
        exit;
    } else {
        $error = "Incorrect email or password. 2 attempts remaining.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Portal | Bella Vista</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="logo.png">
    <style>
        body { 
            background: #F3E9DA; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0;
        }
        .login-wrapper {
            display: flex;
            background: #FBF7F0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 800px;
            max-width: 90%;
            min-height: 500px;
        }
        .login-left {
            background: #3B2A20;
            color: #FBF7F0;
            padding: 50px;
            width: 45%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-left img {
            width: 60px;
            margin-bottom: 20px;
        }
        .login-left h2 {
            font-size: 32px;
            margin-bottom: 15px;
        }
        .login-right {
            padding: 50px;
            width: 55%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-right h2 {
            font-size: 28px;
            margin-bottom: 20px;
        }
        .error-msg {
            background: #F3E9DA;
            border: 1px solid #A9744F;
            padding: 10px;
            border-radius: 5px;
            font-size: 13px;
            margin-bottom: 20px;
            color: #3B2A20;
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <!-- LEFT SIDE -->
        <div class="login-left">
            <img src="logo2.png" alt="Logo">
            <h2>Staff Portal</h2>
            <p style="font-size: 14px; line-height: 1.6; color: #D9BFA3;">
                Authorized spa managers and front desk staff only.
            </p>
        </div>

        <!-- RIGHT SIDE -->
        <div class="login-right">
            <h2>Log in</h2>
            
            <?php if($error): ?>
                <div class="error-msg"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required placeholder="example@gmail.com">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="••••••••">
                </div>
                
                </p>
                <button type="submit" class="btn" style="width: 100%;">Log in</button>
            </form>
            
            <p style="font-size: 12px; margin-top: 20px; color: #6F4E37;">
                Trouble logging in? Ask the spa manager to reset your password.
            </p>
                        <hr style="border: none; border-top: 1px solid #D9BFA3; margin: 20px 0;">
            <p style="font-size: 12px; color: #6F4E37;">
                <a href="index.php" style="color: #3B2A20; text-decoration: underline;">Back to home page</a>
            </p>
        </div>
    </div>

</body>
</html>