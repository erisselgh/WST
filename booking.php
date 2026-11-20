<?php
session_start();
// Check if customer is logged in
$is_logged_in = isset($_SESSION['customer_id']);
$customer_name = $_SESSION['customer_name'] ?? '';
require 'db.php';

$step = 1; 
$message = "";

// Initialize variables for the confirmation screen
$final_ref = "";
$final_total = 0;
$final_massage = "";

// Fetch rooms
$stmt = $pdo->query("SELECT * FROM services");
$rooms = $stmt->fetchAll();

// Handle Step 1 Submission (Selecting Rooms & Massages)
if (isset($_POST['select_service'])) {
    $_SESSION['booking_service_id'] = $_POST['service_id'];
    $_SESSION['booking_date'] = $_POST['date'];
    $_SESSION['booking_time'] = $_POST['time'];
    $_SESSION['booking_guests'] = $_POST['guests'];
    $_SESSION['massage_details'] = isset($_POST['massage_details']) ? $_POST['massage_details'] : 'None selected';
    $_SESSION['grand_total'] = $_POST['grand_total'];
    
    header("Location: booking.php");
    exit;
}

// Handle Step 2 Submission (Confirming Details)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_booking'])) {
    $service_id = $_SESSION['booking_service_id'];
    $date = $_SESSION['booking_date'];
    $time = $_SESSION['booking_time'];
    $guests = (int)$_SESSION['booking_guests'];
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $massage_details = $_SESSION['massage_details'];
    $total_price = $_SESSION['grand_total']; 

    $start_time = strtotime($time);
    
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch();
    
    $duration = 60 * 60; 
    $end_time = date('H:i:s', $start_time + $duration);
    $ref = 'BV-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        $user_id = $_SESSION['customer_id'] ?? NULL;

    $sql = "INSERT INTO bookings (reference_no, user_id, service_id, booking_date, start_time, end_time, guests, total_price, guest_name, mobile, massage_details) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$ref, $user_id, $service_id, $date, $time, $end_time, $guests, $total_price, $name, $mobile, $massage_details])) {
        
        // CLEAR SESSION
        unset($_SESSION['booking_service_id'], $_SESSION['booking_date'], $_SESSION['booking_time'], $_SESSION['booking_guests'], $_SESSION['massage_details'], $_SESSION['grand_total']);
        
        // PRG PATTERN: Redirect immediately and pass data in the URL
        header("Location: booking.php?success=1&ref=" . urlencode($ref) . "&total=" . urlencode($total_price) . "&massage=" . urlencode($massage_details));
        exit;
    }
}

// Check which step to show
if (isset($_GET['success'])) {
    $step = 3;
    $final_ref = htmlspecialchars($_GET['ref'] ?? '');
    // Use (float) to force the value to be a number (0 if empty)
    $final_total = (float)($_GET['total'] ?? 0);
    $final_massage = htmlspecialchars($_GET['massage'] ?? '');
} elseif (isset($_SESSION['booking_service_id'])) {
    $step = 2;
}

// Reset Logic
if(isset($_GET['reset'])) {
    unset($_SESSION['booking_service_id'], $_SESSION['booking_date'], $_SESSION['booking_time'], $_SESSION['booking_guests'], $_SESSION['massage_details'], $_SESSION['grand_total']);
    header("Location: booking.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment - Bella Vista Oasis Spa</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="logo.png">
    <style>
        .step-container { max-width: 900px; margin: 50px auto; background: #F3E9DA; padding: 40px; border-radius: 20px; }
        .service-option { display: block; padding: 20px; background: #FBF7F0; border: 1px solid #D9BFA3; border-radius: 10px; margin-bottom: 15px; cursor: pointer; transition: all 0.3s; }
        input[type="radio"]:checked + .service-option { border-color: #6F4E37; background: #fff; box-shadow: 0 0 0 2px #6F4E37; }
        .hidden-radio { display: none; }
        
        /* Massage Checkbox Styles */
        .massage-grid { display: grid; grid-template-columns: 1fr; gap: 10px; background: #FBF7F0; padding: 20px; border-radius: 10px; border: 1px solid #D9BFA3; }
        .massage-item { display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #F3E9DA; }
        .massage-item:last-child { border-bottom: none; }
        .massage-checkbox { display: flex; align-items: center; gap: 10px; cursor: pointer; }
        .massage-checkbox input[type="checkbox"] { width: 20px; height: 20px; accent-color: #6F4E37; cursor: pointer; }
        .massage-label strong { font-size: 15px; color: #3B2A20; display: block; }
        
        .duration-select { padding: 8px; border-radius: 5px; border: 1px solid #D9BFA3; font-family: 'Jost', sans-serif; color: #3B2A20; cursor: pointer; }
        
        .summary-box { background: #3B2A20; color: #FBF7F0; padding: 20px; border-radius: 10px; margin-top: 20px; display: flex; justify-content: space-between; align-items: center; font-size: 20px; }
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

    <div class="step-container">
        
        <!-- STEP 1: SELECT ROOM & MASSAGES -->
        <?php if($step === 1): ?>
            <h2 style="text-align: center; margin-bottom: 10px;">Select Room & Treatments</h2>
            <p style="text-align: center; color: #6F4E37; margin-bottom: 30px;">Customize your spa experience.</p>
            
            <form method="POST" id="bookingForm">
                <h4 style="margin-bottom: 15px;">1. Choose a Room</h4>
                <?php foreach($rooms as $r): ?>
                    <label>
                        <input type="radio" name="service_id" value="<?= $r['id'] ?>" class="hidden-radio" data-price="<?= $r['price'] ?>" onchange="calculateTotal()" required>
                        <div class="service-option">
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <strong><?= $r['name'] ?> (Max <?= $r['max_capacity'] ?> guests)</strong>
                                <span style="color: #6F4E37; font-weight: 500;">₱<?= number_format($r['price'], 2) ?></span>
                            </div>
                        </div>
                    </label>
                <?php endforeach; ?>

                <h4 style="margin: 30px 0 15px 0;">2. Add Massages (Select duration)</h4>
                
                <div class="massage-grid">
                    <!-- Swedish -->
                    <div class="massage-item">
                        <label class="massage-checkbox">
                            <input type="checkbox" name="massage[]" value="Swedish" data-price-1="800" data-price-2="1500" onchange="calculateTotal()">
                            <div class="massage-label"><strong>Swedish</strong></div>
                        </label>
                        <select class="duration-select" onchange="calculateTotal()">
                            <option value="1">1 hour (₱800)</option>
                            <option value="2">2 hours (₱1,500)</option>
                        </select>
                    </div>

                    <!-- Foot Reflexology -->
                    <div class="massage-item">
                        <label class="massage-checkbox">
                            <input type="checkbox" name="massage[]" value="Foot Reflexology" data-price-1="800" data-price-2="1500" onchange="calculateTotal()">
                            <div class="massage-label"><strong>Foot Reflexology</strong></div>
                        </label>
                        <select class="duration-select" onchange="calculateTotal()">
                            <option value="1">1 hour (₱800)</option>
                            <option value="2">2 hours (₱1,500)</option>
                        </select>
                    </div>

                    <!-- Shiatsu -->
                    <div class="massage-item">
                        <label class="massage-checkbox">
                            <input type="checkbox" name="massage[]" value="Shiatsu" data-price-1="900" data-price-2="1700" onchange="calculateTotal()">
                            <div class="massage-label"><strong>Shiatsu</strong></div>
                        </label>
                        <select class="duration-select" onchange="calculateTotal()">
                            <option value="1">1 hour (₱900)</option>
                            <option value="2">2 hours (₱1,700)</option>
                        </select>
                    </div>

                    <!-- Hilot -->
                    <div class="massage-item">
                        <label class="massage-checkbox">
                            <input type="checkbox" name="massage[]" value="Hilot" data-price-1="1000" data-price-2="1900" onchange="calculateTotal()">
                            <div class="massage-label"><strong>Hilot (Filipino)</strong></div>
                        </label>
                        <select class="duration-select" onchange="calculateTotal()">
                            <option value="1">1 hour (₱1,000)</option>
                            <option value="2">2 hours (₱1,900)</option>
                        </select>
                    </div>

                    <!-- Aromatherapy -->
                    <div class="massage-item">
                        <label class="massage-checkbox">
                            <input type="checkbox" name="massage[]" value="Aromatherapy" data-price-1="1000" data-price-2="1900" onchange="calculateTotal()">
                            <div class="massage-label"><strong>Aromatherapy</strong></div>
                        </label>
                        <select class="duration-select" onchange="calculateTotal()">
                            <option value="1">1 hour (₱1,000)</option>
                            <option value="2">2 hours (₱1,900)</option>
                        </select>
                    </div>

                    <!-- Deep Tissue -->
                    <div class="massage-item">
                        <label class="massage-checkbox">
                            <input type="checkbox" name="massage[]" value="Deep Tissue" data-price-1="1100" data-price-2="2100" onchange="calculateTotal()">
                            <div class="massage-label"><strong>Deep Tissue</strong></div>
                        </label>
                        <select class="duration-select" onchange="calculateTotal()">
                            <option value="1">1 hour (₱1,100)</option>
                            <option value="2">2 hours (₱2,100)</option>
                        </select>
                    </div>

                    <!-- Hot Stone -->
                    <div class="massage-item">
                        <label class="massage-checkbox">
                            <input type="checkbox" name="massage[]" value="Hot Stone" data-price-1="1300" data-price-2="2500" onchange="calculateTotal()">
                            <div class="massage-label"><strong>Hot Stone</strong></div>
                        </label>
                        <select class="duration-select" onchange="calculateTotal()">
                            <option value="1">1 hour (₱1,300)</option>
                            <option value="2">2 hours (₱2,500)</option>
                        </select>
                    </div>
                </div>

                <h4 style="margin: 30px 0 15px 0;">3. Date & Time</h4>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" required min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="form-group">
                        <label>Start time</label>
                        <input type="time" name="time" required min="10:00" max="21:30">
                    </div>
                </div>
                <div class="form-group">
                    <label>Number of Guests (Max 6)</label>
                    <input type="number" name="guests" id="guestInput" min="1" max="6" value="1" required oninput="calculateTotal()">
                </div>

                <input type="hidden" name="massage_details" id="massage_details_input" value="None selected">
                <input type="hidden" name="grand_total" id="grand_total_input" value="0">

                <div class="summary-box">
                    <span>Estimated Total:</span>
                    <strong id="display_total">₱0.00</strong>
                </div>

                <button type="submit" name="select_service" class="btn" style="width: 100%; margin-top: 20px;">Next: Enter Details</button>
            </form>
            
            <script>
                function calculateTotal() {
                    // 1. Room Base Price
                    let roomPrice = 0;
                    let roomId = null;
                    let radios = document.getElementsByName('service_id');
                    for (let r of radios) {
                        if (r.checked) { 
                            roomPrice = parseFloat(r.getAttribute('data-price')); 
                            roomId = r.value; 
                        }
                    }

                    // 2. Massages Calculation
                    let massageTotal = 0;
                    let selectedNames = [];
                    let items = document.querySelectorAll('.massage-item');
                    
                    items.forEach((item) => {
                        let cb = item.querySelector('input[type="checkbox"]');
                        if (cb.checked) {
                            let durationSelect = item.querySelector('select');
                            let duration = durationSelect.value; // "1" or "2"
                            
                            // Get the price based on duration
                            let price = duration === "1" ? parseFloat(cb.getAttribute('data-price-1')) : parseFloat(cb.getAttribute('data-price-2'));
                            
                            massageTotal += price;
                            selectedNames.push(`${cb.value} (${duration} hr)`);
                        }
                    });

                    // 3. Extra Person Fees (Family Lounge, ID=3)
                    let extraFee = 0;
                    let guests = parseInt(document.getElementById('guestInput').value) || 1;
                    
                    if (guests > 6) { guests = 6; document.getElementById('guestInput').value = 6; }
                    
                    if (roomId == "3" && guests > 4) {
                        extraFee = (guests - 4) * 750;
                    }

                    // 4. Grand Total
                    let grandTotal = roomPrice + massageTotal + extraFee;

                    // 5. Update UI
                    document.getElementById('display_total').innerText = '₱' + grandTotal.toFixed(2);
                    document.getElementById('grand_total_input').value = grandTotal;
                    document.getElementById('massage_details_input').value = selectedNames.length > 0 ? selectedNames.join(', ') : 'None selected';
                }
            </script>

        <!-- STEP 2: ENTER DETAILS -->
        <?php elseif($step === 2): ?>
            <div style="background: #FBF7F0; padding: 20px; border-radius: 10px; margin-bottom: 30px; border: 1px solid #D9BFA3;">
                <div style="display:flex; justify-content:space-between; margin-bottom: 10px;">
                    <span style="font-size: 12px; color: #A9744F; font-weight: bold; text-transform: uppercase;">Your Selection</span>
                    <a href="booking.php?reset=1" style="font-size: 13px; color: #6F4E37; text-decoration: underline;">Change</a>
                </div>
                <p style="font-size: 16px; margin-bottom: 5px;"><strong>Massages:</strong> <?= htmlspecialchars($_SESSION['massage_details']) ?></p>
                <p style="font-size: 16px; color: #6F4E37;"><strong>Date:</strong> <?= date('M d, Y', strtotime($_SESSION['booking_date'])) ?> at <?= date('g:i A', strtotime($_SESSION['booking_time'])) ?></p>
                <h3 style="margin-top: 15px; color: #3B2A20; border-top: 1px solid #D9BFA3; padding-top: 15px;">Total Amount: ₱<?= number_format($_SESSION['grand_total'], 2) ?></h3>
            </div>

                <form method="POST" onsubmit="return confirmBooking();">
                <h4 style="margin-bottom: 15px;">Health Declaration & Contact Info</h4>
                
                <div class="form-group" style="display:flex; gap:15px; flex-wrap:wrap; margin-bottom: 25px;">
                    <label><input type="checkbox" name="health[]" value="Medical"> Medical condition</label>
                    <label><input type="checkbox" name="health[]" value="Injury"> Recent injury</label>
                    <label><input type="checkbox" name="health[]" value="Pregnancy"> Pregnancy</label>
                </div>

                                <div class="grid-2">
                    <div class="form-group">
                        <label>Full Name</label>
                        <?php if($is_logged_in): ?>
                            <input type="text" name="name" value="<?= htmlspecialchars($customer_name) ?>" readonly style="background: #e9ecef; cursor: not-allowed;">
                            <small style="color: #6F4E37;">Logged in as <?= htmlspecialchars($customer_name) ?></small>
                        <?php else: ?>
                            <input type="text" name="name" required placeholder="e.g. Jane Doe">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Mobile Number</label>
                        <input type="text" name="mobile" required placeholder="09123456789">
                    </div>
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 20px;">
                    <input type="checkbox" id="terms" required style="width: auto; cursor: pointer;">
                    <label for="terms" style="margin: 0; font-weight: normal;">I agree to the <a href="#" style="color: #6F4E37; text-decoration: underline;">Terms and Conditions</a></label>
                </div>

                <button type="submit" name="confirm_booking" class="btn" style="width: 100%; margin-top: 20px;">Confirm Booking</button>
            </form>

                   <!-- STEP 3: CONFIRMATION -->
        <?php elseif($step === 3): ?>
            <div style="text-align: center; padding: 20px;">
                <h2 style="color: #6F4E37; margin-bottom: 10px;">Booking Confirmed!</h2>
                <p style="margin-bottom: 20px;">Thank you for choosing Bella Vista Oasis Spa.</p>
                
                <div style="background: #3B2A20; color: #FBF7F0; padding: 30px; border-radius: 15px; margin: 20px auto; max-width: 400px;">
                    <p style="font-size: 14px; color: #D9BFA3; text-transform: uppercase; letter-spacing: 1px;">Your Reference Code</p>
                    <h1 style="font-size: 36px; margin: 10px 0;"><?= $final_ref ?></h1>
                    <p style="font-size: 18px;">Total: ₱<?= number_format($final_total, 2) ?></p>
                    <p style="font-size: 14px; color: #D9BFA3; margin-top: 10px;"><?= $final_massage ?></p>
                </div>
                
                <p style="font-size: 14px; color: #6F4E37; margin-bottom: 30px;">Please save this reference code. You will need it to manage or cancel your booking.</p>
                <a href="index.php" class="btn">Return to Home</a>
            </div>
            
            <!-- This script cleans the URL so if they refresh, it doesn't resubmit -->
            <script>
                if (window.history.replaceState) {
                    window.history.replaceState(null, null, window.location.pathname);
                }
            </script>
        <?php endif; ?>
        

    </div>
        <script>
        function confirmBooking() {
            // This will show a popup with OK and Cancel buttons
            return confirm("Confirm Booking?\n\nClick OK to finalize your appointment, or Cancel to review your details.");
        }
    </script>
    
</body>
</html>