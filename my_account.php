<?php
session_start();
require 'db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_auth.php");
    exit;
}

$user_id = $_SESSION['customer_id'];
$name = $_SESSION['customer_name'];

// Fetch their bookings
$stmt = $pdo->prepare("SELECT b.*, s.name as service_name FROM bookings b JOIN services s ON b.service_id = s.id WHERE b.user_id = ? ORDER BY b.booking_date DESC");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account | Bella Vista</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="logo.png">
</head>
<body>
    <nav>
        <div class="logo">
            <a href="index.php" style="text-decoration:none; color:inherit; display:flex; align-items:center;">
                <img src="logo.png" alt="Logo" style="width: 40px; height: 40px; object-fit: contain; margin-right: 10px;"> 
                Bella Vista Oasis Spa
            </a>
        </div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="booking.php" class="btn">Book Appointment</a>
            <a href="logout.php" style="color: #A9744F;">Logout</a>
        </div>
    </nav>

    <div style="max-width: 900px; margin: 50px auto; padding: 0 20px;">
        <h1>Welcome back, <?= htmlspecialchars($name) ?></h1>
        <p style="color: #6F4E37; margin-bottom: 30px;">Manage your appointments and view your booking history.</p>

        <h3 style="margin-bottom: 15px;">Your Bookings</h3>
        <?php if(empty($bookings)): ?>
            <p>You have no bookings yet.</p>
        <?php else: ?>
            <div style="background: #F3E9DA; padding: 20px; border-radius: 15px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid #D9BFA3; text-align: left;">
                        <th style="padding: 10px;">Ref</th>
                        <th style="padding: 10px;">Date & Time</th>
                        <th style="padding: 10px;">Service</th>
                        <th style="padding: 10px;">Total</th>
                    </tr>
                    <?php foreach($bookings as $b): ?>
                    <tr style="border-bottom: 1px solid #D9BFA3;">
                        <td style="padding: 10px;"><?= $b['reference_no'] ?></td>
                        <td style="padding: 10px;"><?= date('M d, Y', strtotime($b['booking_date'])) ?> at <?= date('g:i A', strtotime($b['start_time'])) ?></td>
                        <td style="padding: 10px;"><?= $b['service_name'] ?><br><small><?= $b['massage_details'] ?></small></td>
                        <td style="padding: 10px;">₱<?= number_format($b['total_price'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>