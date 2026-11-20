<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require 'db.php';

$error_message = "";
$success_message = "";

// Handle Therapist Assignment with Conflict Checking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_therapist'])) {
    $booking_id = $_POST['booking_id'];
    $therapist = $_POST['therapist'];
    
    // 1. Get the date and time of the current booking we are trying to assign
    $stmt = $pdo->prepare("SELECT booking_date, start_time, end_time FROM bookings WHERE id = ?");
    $stmt->execute([$booking_id]);
    $current_booking = $stmt->fetch();
    
    if ($current_booking) {
        $date = $current_booking['booking_date'];
        $new_start = $current_booking['start_time'];
        $new_end = $current_booking['end_time'];
        
        // 2. Check if this therapist is already booked on this date with overlapping times
        // Logic: (New Start < Existing End) AND (New End > Existing Start) = OVERLAP
        $check_sql = "SELECT reference_no, start_time, end_time FROM bookings 
                      WHERE therapist = ? 
                      AND booking_date = ? 
                      AND id != ? 
                      AND (start_time < ? AND end_time > ?)";
        
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([$therapist, $date, $booking_id, $new_end, $new_start]);
        $conflict = $check_stmt->fetch();
        
        if ($conflict) {
            $error_message = "Conflict! $therapist is already assigned to Booking {$conflict['reference_no']} from " . 
                             date('g:i A', strtotime($conflict['start_time'])) . " to " . 
                             date('g:i A', strtotime($conflict['end_time'])) . " on this date.";
        } else {
            // 3. No conflict, safe to assign
            $update_stmt = $pdo->prepare("UPDATE bookings SET therapist = ? WHERE id = ?");
            $update_stmt->execute([$therapist, $booking_id]);
            $success_message = "Successfully assigned $therapist to the booking.";
        }
    }
}

// Fetch all bookings
$stmt = $pdo->prepare("SELECT b.*, s.name as service_name FROM bookings b JOIN services s ON b.service_id = s.id ORDER BY b.booking_date DESC, b.start_time ASC");
$stmt->execute();
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Schedule | Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="logo.png">
</head>
<body>
    <div class="admin-layout">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div style="display:flex; align-items:center; color:#FBF7F0; margin-bottom:30px;">
                <img src="logo.png" alt="Logo" style="width: 35px; height: 35px; object-fit: contain; margin-right: 10px;">
                <h2 style="margin: 0;">Admin</h2>
            </div>
            <a href="admin.php" class="active">Schedule</a>
            <a href="admin_new.php">New Reservation</a>
            <a href="admin_therapists.php">Therapists</a>
            <a href="logout.php">Sign out</a>
        </div>

        <div class="main-content">
            <h1>Appointment Schedule</h1>
            
            <!-- Show Notifications -->
            <?php if($error_message): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    <strong>Error:</strong> <?= $error_message ?>
                </div>
            <?php endif; ?>
            
            <?php if($success_message): ?>
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                    <strong>Success!</strong> <?= $success_message ?>
                </div>
            <?php endif; ?>

            <div style="background: #F3E9DA; padding: 20px; border-radius: 15px; margin-top: 20px; overflow-x:auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    
                    <tr style="border-bottom: 1px solid #D9BFA3;">
                        <th style="padding: 15px 10px;">Ref</th>
                        <th style="padding: 15px 10px;">Guest</th>
                        <th style="padding: 15px 10px;">Date</th>
                        <th style="padding: 15px 10px;">Service</th>
                        <th style="padding: 15px 10px;">Time</th>
                        <th style="padding: 15px 10px;">Guests</th>
                        <th style="padding: 15px 10px;">Total</th>
                        <th style="padding: 15px 10px;">Assign Therapist</th>
                    </tr>

                    <?php foreach($bookings as $b): ?>
                    <tr style="border-bottom: 1px solid #D9BFA3;">
                        <td style="padding: 15px 10px; white-space: nowrap;">
                            <?= $b['reference_no'] ?>
                        </td>
                        <td style="padding: 15px 10px;">
                            <strong><?= htmlspecialchars($b['guest_name'] ?? 'N/A') ?></strong><br>
                            <span style="font-size: 12px; color: #6F4E37;"><?= htmlspecialchars($b['mobile'] ?? '') ?></span>
                        </td>
                        <td style="padding: 15px 10px; white-space: nowrap;">
                            <?= date('M d, Y', strtotime($b['booking_date'])) ?>
                        </td>
                        <td style="padding: 15px 10px; width: 20%;">
                            <?= $b['service_name'] ?>
                            <?php if(!empty($b['massage_details'])): ?>
                                <br><span style="font-size: 12px; color: #A9744F;"><i><?= htmlspecialchars($b['massage_details']) ?></i></span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 15px 10px; white-space: nowrap;">
                            <?= date('g:i A', strtotime($b['start_time'])) ?> - <?= date('g:i A', strtotime($b['end_time'])) ?>
                        </td>
                        <td style="padding: 15px 10px; text-align: center;">
                            <?= $b['guests'] ?>
                        </td>
                        <td style="padding: 15px 10px; white-space: nowrap;">
                            ₱<?= number_format($b['total_price'],2) ?>
                        </td>
                        <td style="padding: 15px 10px;">
                            <form method="POST" style="display:flex; gap:5px; align-items:center;">
                                <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                <select name="therapist" style="padding: 8px; border-radius: 5px; border: 1px solid #D9BFA3; font-family: 'Jost', sans-serif; font-size: 14px;" required>
                                    <option value="">Select...</option>
                                    <option value="Maria Santos" <?= $b['therapist'] == 'Maria Santos' ? 'selected' : '' ?>>Maria Santos</option>
                                    <option value="John Doe" <?= $b['therapist'] == 'John Doe' ? 'selected' : '' ?>>John Doe</option>
                                    <option value="Jane Smith" <?= $b['therapist'] == 'Jane Smith' ? 'selected' : '' ?>>Jane Smith</option>
                                </select>
                                <button type="submit" name="assign_therapist" class="btn" style="padding: 8px 12px; font-size: 14px;">Assign</button>
                            </form>
                            
                            <!-- Show current assignment -->
                            <?php if($b['therapist']): ?>
                                <div style="font-size: 12px; margin-top: 8px; color: #6F4E37;">
                                    <strong>Assigned:</strong> <?= $b['therapist'] ?>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if(empty($bookings)): ?>
                        <tr><td colspan="8" style="padding:30px; text-align:center;">No bookings found.</td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>