<?php
session_start();
if (!isset($_SESSION['driver_id'])) {
    header("Location: driver_login.php");
    exit();
}

$conn = new mysqli("localhost","root","","shuttle_db");
$driver_id = $_SESSION['driver_id'];

// Fetch driver info
$stmt = $conn->prepare("SELECT username, approved FROM driver_accounts WHERE id=?");
$stmt->bind_param("i", $driver_id);
$stmt->execute();
$result = $stmt->get_result();
$driver = $result->fetch_assoc();
if (!$driver) die("Driver not found.");

// Fetch assigned shuttle
$shuttle_stmt = $conn->prepare("
    SELECT s.shuttle_name, s.plate_number 
    FROM shuttle_assignments sa 
    JOIN shuttles s ON sa.shuttle_id = s.id
    WHERE sa.driver_id=?
    LIMIT 1
");
$shuttle_stmt->bind_param("i", $driver_id);
$shuttle_stmt->execute();
$shuttle_result = $shuttle_stmt->get_result();
$shuttle = $shuttle_result->fetch_assoc();

// Handle GPS request submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_gps'])) {
    // Check if a pending notification already exists
    $check = $conn->prepare("SELECT id FROM driver_notifications WHERE driver_id=? AND status='pending' ORDER BY id DESC LIMIT 1");
    $check->bind_param("i", $driver_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO driver_notifications (driver_id, message) VALUES (?, ?)");
        $msg = "Driver {$driver['username']} requests location access for GPS tracking.";
        $stmt->bind_param("is", $driver_id, $msg);
        $stmt->execute();
    }

    // Redirect to GPS page
    header("Location: driver_device.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Driver Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif;}
body{height:100vh;background:linear-gradient(135deg,#1e3c72,#2a5298);display:flex;justify-content:center;align-items:center;color:white;}
.dashboard{width:95%;max-width:420px;padding:30px;border-radius:25px;background:rgba(255,255,255,0.1);backdrop-filter:blur(15px);box-shadow:0 20px 40px rgba(0,0,0,0.4);animation:fade 0.8s ease;}
@keyframes fade{from{opacity:0; transform:translateY(20px);} to{opacity:1; transform:translateY(0);}}
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
.header h3{font-weight:600;}
.logout{text-decoration:none;color:white;font-size:13px;padding:6px 12px;border-radius:8px;background:rgba(255,0,0,0.7);transition:0.3s;}
.logout:hover{background:red;}
.status-card{padding:20px;border-radius:20px;margin-bottom:20px;text-align:center;}
.approved{background:linear-gradient(135deg,#00c853,#009624);}
.pending{background:linear-gradient(135deg,#ff9800,#f57c00);}
.status-card h4{margin-bottom:10px;}
.btn{display:block;width:100%;padding:14px;border:none;border-radius:15px;font-size:15px;font-weight:bold;cursor:pointer;transition:0.3s;text-decoration:none;text-align:center;}
.btn-primary{background:linear-gradient(90deg,#00f2fe,#4facfe);color:white;}
.btn-primary:hover{transform:scale(1.05);box-shadow:0 10px 20px rgba(0,0,0,0.4);}
.footer{margin-top:15px;text-align:center;font-size:12px;opacity:0.8;}
@media(max-width:400px){.dashboard{padding:20px;}}
</style>
</head>
<body>

<div class="dashboard">

    <div class="header">
        <h3>🚐 Driver Panel</h3>
        <a href="driver_logout.php" class="logout">Logout</a>
    </div>

    <h4 style="margin-bottom:15px;">Welcome, <?= htmlspecialchars($driver['username']) ?> 👋</h4>

    <?php if(!$shuttle): ?>
        <div class="status-card pending">
            <h4>⚠️ No Shuttle Assigned</h4>
            <p>Please wait for admin to assign you to a shuttle.</p>
        </div>
    <?php else: ?>
        <div class="status-card <?= $driver['approved'] ? 'approved' : 'pending' ?>">
            <h4><?= $driver['approved'] ? '✅ Account Approved' : '⏳ Approval Pending' ?></h4>
            <p>Shuttle: <?= htmlspecialchars($shuttle['shuttle_name']) ?> (<?= htmlspecialchars($shuttle['plate_number']) ?>)</p>
            <p><?= $driver['approved'] ? 'Your GPS tracking is active and ready.' : 'Please wait for admin approval before starting GPS.' ?></p>
        </div>

        <?php if($driver['approved']): ?>
            <form method="POST">
                <input type="hidden" name="request_gps" value="1">
                <button type="submit" class="btn btn-primary">📍 Start GPS Tracking</button>
            </form>
        <?php endif; ?>

    <?php endif; ?>

    <div class="footer">
        Shuttle Tracking System • Capstone Project
    </div>

</div>

</body>
</html>