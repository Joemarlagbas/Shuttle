<?php
session_start();
if (!isset($_SESSION['driver_id'])) {
    header("Location: driver_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "shuttle_db");
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
    SELECT s.id, s.shuttle_name, s.plate_number
    FROM shuttle_assignments sa
    JOIN shuttles s ON sa.shuttle_id = s.id
    WHERE sa.driver_id=? LIMIT 1
");
$shuttle_stmt->bind_param("i", $driver_id);
$shuttle_stmt->execute();
$shuttle_result = $shuttle_stmt->get_result();
$shuttle = $shuttle_result->fetch_assoc();
if (!$shuttle) die("You are not assigned to a shuttle yet.");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Driver GPS Tracking</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
body{font-family:'Segoe UI',sans-serif;padding:20px;background:#f0f8ff;}
.status-box{background:#e6f0ff;padding:20px;border-radius:10px;text-align:center;margin-top:30px;}
.btn-start{padding:12px 20px;font-size:16px;border-radius:10px;}
</style>
</head>
<body>

<h3>🚐 Tracking Shuttle: <?= htmlspecialchars($shuttle['shuttle_name']) ?> (<?= htmlspecialchars($shuttle['plate_number']) ?>)</h3>

<div class="status-box" id="statusBox">
    Initializing GPS...
</div>

<button class="btn btn-primary btn-start mt-3" id="startBtn">Start GPS Tracking</button>

<script>
let shuttleId = <?= $shuttle['id'] ?>;
let driverId = <?= $driver_id ?>;
let watchId = null;
let statusBox = document.getElementById('statusBox');
let startBtn = document.getElementById('startBtn');

startBtn.addEventListener('click', function(){
    if(!navigator.geolocation){
        statusBox.innerText = "❌ Geolocation not supported by your browser.";
        return;
    }

    statusBox.innerText = "📍 Requesting GPS access...";
    navigator.geolocation.getCurrentPosition(
        function(pos){
            statusBox.innerText = "✅ GPS access granted. Tracking started.";
            startBtn.disabled = true;

            // Start watching position
            watchId = navigator.geolocation.watchPosition(sendLocation, gpsError, {
                enableHighAccuracy: true,
                maximumAge: 5000,
                timeout: 10000
            });
        },
        function(err){
            statusBox.innerText = "❌ GPS access denied or unavailable.";
        }
    );
});

function sendLocation(position){
    let lat = position.coords.latitude;
    let lng = position.coords.longitude;

    statusBox.innerText = `📍 Sending GPS: Lat ${lat.toFixed(6)}, Lng ${lng.toFixed(6)}`;

    fetch('save_location.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({
            shuttle_id: shuttleId,
            driver_id: driverId,
            lat: lat,
            lng: lng
        })
    })
    .then(r=>r.json())
    .then(data=>{
        if(data.status !== 'success') console.error(data.message);
    });
}

function gpsError(err){
    statusBox.innerText = "❌ Error getting GPS: " + err.message;
}
</script>

</body>
</html>