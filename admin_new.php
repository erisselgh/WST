<?php
session_start();

// Check if the user is NOT logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit; // Stop executing the rest of the page
}

require 'db.php';
// ... rest of your code
require 'db.php';

// Fetch services for the dropdown
$stmt = $pdo->query("SELECT * FROM services");
$services = $stmt->fetchAll();

// Handle form submission for walk-in/phone booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = $_POST['service_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $guests = (int)$_POST['guests'];
    $guest_name = $_POST['guest_name']; 
    $mobile = $_POST['mobile'];
    
    // Get service details for calculation
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch();
    
    $total_price = $service['price'];
    if ($service['code'] == 'FL-1' && $guests > 4) {
        $total_price += (($guests - 4) * 750);
    }
    
    // Calculate end time
    $start = strtotime($time);
    $end = date('H:i:s', $start + ($service['duration_minutes'] * 60));
    
    // Generate Reference
    $ref = 'WALK-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

    // Save to DB (Status is automatically 'confirmed' since it's an admin booking)
    $sql = "INSERT INTO bookings (reference_no, service_id, booking_date, start_time, end_time, guests, total_price, guest_name, mobile, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed')";
    
    // Notice I added $guest_name and $mobile to the execute array
    $pdo->prepare($sql)->execute([$ref, $service_id, $date, $time, $end, $guests, $total_price, $guest_name, $mobile]);
    
    echo "<script>alert('Walk-in reservation saved successfully!'); window.location.href='admin.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Reservation | Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="logo.png">
</head>
<body>
    <div class="admin-layout">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div style="display:flex; align-items:center; color:#FBF7F0; margin-bottom:30px;">
                <img src="logo2.png" alt="Logo" style="width: 35px; height: 35px; object-fit: contain; margin-right: 10px;">
                <h2 style="margin: 0;">Admin</h2>
            </div>
            <a href="admin.php">Schedule</a>
            <a href="admin_new.php" class="active">New Reservation</a>
            <a href="admin_therapists.php">Therapists</a>
            <a href="logout.php">Sign out</a>
        </div>

        <div class="main-content">
            <h1>New reservation</h1>
            <p style="margin-bottom: 30px;">For walk-in guests and phone bookings made at the front desk.</p>

            <div class="grid-2">
                <!-- FORM CARD -->
                <div class="card" style="background: #F3E9DA;">
                    <form method="POST">
                        <div class="form-group">
                            <label>Booking source</label>
                            <select disabled style="opacity: 0.7;"><option>Walk-in / Phone</option></select>
                        </div>
                        <div class="form-group">
                            <label>Guest name</label>
                            <input type="text" name="guest_name" required placeholder="Enter guest name">
                        </div>
                        <div class="form-group">
                            <label>Mobile number</label>
                            <input type="text" name="mobile" required placeholder="09123456789">
                        </div>
                        <div class="form-group">
                            <label>Service</label>
                            <select name="service_id" required>
                                <?php foreach($services as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= $s['name'] ?> (₱<?= $s['price'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="grid-2">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" name="date" required min="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="form-group">
                                <label>Start time</label>
                                <!-- Operating hours restricted here -->
                                <input type="time" name="time" required min="10:00" max="21:30">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Guests</label>
                            <input type="number" name="guests" min="1" max="6" value="1" required>
                        </div>
                        <button type="submit" class="btn" style="width: 100%;">Save reservation</button>
                    </form>
                </div>

                <!-- SUMMARY CARD -->
                <div class="card" style="background: #3B2A20; color: #FBF7F0;">
                    <h2>Summary</h2>
                    <p style="margin-top: 15px; font-size: 18px;">Front Desk Booking</p>
                    <p style="color: #D9BFA3;">This booking will immediately appear on the schedule calendar and block out the room for the selected time.</p>
                    
                    <hr style="border-color: #6F4E37; margin: 20px 0;">
                    
                    <p style="font-size: 14px;"><strong>Note:</strong> A mandatory 30-minute cleaning gap will be automatically assumed after this session during scheduling.</p>
                    
                    <a href="admin.php" class="btn" style="display:block; text-align:center; margin-top: 30px; background: #D9BFA3; color: #3B2A20 !important;">Back to Schedule</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>