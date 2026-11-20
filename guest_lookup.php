<?php
session_start();
require 'db.php';

$booking = null;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ref = strtoupper(trim($_POST['reference']));
    $mobile = trim($_POST['mobile']);
    
    $stmt = $pdo->prepare("SELECT b.*, s.name as service_name FROM bookings b JOIN services s ON b.service_id = s.id WHERE b.reference_no = ? AND b.mobile = ?");
    $stmt->execute([$ref, $mobile]);
    $booking = $stmt->fetch();
    
    if (!$booking) {
        $error = "No booking found matching that reference number and mobile number.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find My Booking | Bella Vista</title>
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
    </nav>

    <div style="max-width: 600px; margin: 80px auto; background: #F3E9DA; padding: 40px; border-radius: 15px;">
        <h2 style="text-align: center; margin-bottom: 10px;">Find my booking</h2>
        <p style="text-align: center; color: #6F4E37; margin-bottom: 30px;">For guests: view your reservation without an account.</p>

        <?php if($error): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 14px;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" style="background: #FBF7F0; padding: 30px; border-radius: 10px; border: 1px solid #D9BFA3;">
            <div class="form-group">
                <label>Booking Reference</label>
                <input type="text" name="reference" required placeholder="e.g. BV-1234">
            </div>
            <div class="form-group">
                <label>Mobile number used when booking</label>
                <input type="text" name="mobile" required placeholder="09123456789">
            </div>
            <button type="submit" class="btn" style="width: 100%;">Find booking</button>
        </form>

        <?php if($booking): ?>
            <div style="margin-top: 30px; background: #3B2A20; color: #FBF7F0; padding: 25px; border-radius: 10px;">
                <h3 style="color: #A9744F; margin-bottom: 10px;">Booking Found!</h3>
                <p><strong>Ref:</strong> <?= htmlspecialchars($booking['reference_no']) ?></p>
                <p><strong>Name:</strong> <?= htmlspecialchars($booking['guest_name']) ?></p>
                <p><strong>Date & Time:</strong> <?= date('M d, Y', strtotime($booking['booking_date'])) ?> at <?= date('g:i A', strtotime($booking['start_time'])) ?></p>
                <p><strong>Service:</strong> <?= $booking['service_name'] ?> (<?= $booking['massage_details'] ?>)</p>
                <p style="font-size: 20px; margin-top: 15px; border-top: 1px solid #6F4E37; padding-top: 15px;"><strong>Total:</strong> ₱<?= number_format($booking['total_price'], 2) ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>