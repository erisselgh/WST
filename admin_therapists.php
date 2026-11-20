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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Therapists | Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="logo.png">
</head>
<body>
    <div class="admin-layout">
        <!-- SIDEBAR (Updated to match all pages) -->
        <div class="sidebar">
            <div style="display:flex; align-items:center; color:#FBF7F0; margin-bottom:30px;">
                <img src="logo2.png" alt="Logo" style="width: 35px; height: 35px; object-fit: contain; margin-right: 10px;">
                <h2 style="margin: 0;">Admin</h2>
            </div>
            <a href="admin.php">Schedule</a>
            <a href="admin_new.php">New Reservation</a>
            <a href="admin_therapists.php" class="active">Therapists</a>
            <a href="logout.php">Sign out</a>
        </div>

        <div class="main-content">
            <h1>Therapist Roster</h1>
            <p style="margin-bottom: 30px;">View therapist availability and status.</p>

            <div style="background: #F3E9DA; padding: 20px; border-radius: 15px;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <tr style="border-bottom: 1px solid #D9BFA3;">
                        <th style="padding: 10px;">Therapist Name</th>
                        <th style="padding: 10px;">Specialty</th>
                        <th style="padding: 10px;">Current Status</th>
                    </tr>
                    <!-- Hardcoded sample therapists for the demo -->
                    <tr style="border-bottom: 1px solid #D9BFA3;">
                        <td style="padding: 15px 10px;">Maria Santos</td>
                        <td>Deep Tissue, Swedish</td>
                        <td><span style="background: #6F4E37; color: white; padding: 5px 10px; border-radius: 5px; font-size: 12px;">Available</span></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #D9BFA3;">
                        <td style="padding: 15px 10px;">John Doe</td>
                        <td>Hot Stone, Aromatherapy</td>
                        <td><span style="background: #3B2A20; color: white; padding: 5px 10px; border-radius: 5px; font-size: 12px;">In Session</span></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #D9BFA3;">
                        <td style="padding: 15px 10px;">Jane Smith</td>
                        <td>Prenatal, Reflexology</td>
                        <td><span style="background: #6F4E37; color: white; padding: 5px 10px; border-radius: 5px; font-size: 12px;">Available</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>