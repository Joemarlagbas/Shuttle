<?php
$conn = new mysqli("localhost", "root", "", "shuttle_db");
if ($conn->connect_error) die("Database connection failed");

// Fetch active shuttles with assigned drivers
$shuttles = $conn->query("
    SELECT 
        s.id AS shuttle_id, 
        s.shuttle_name, 
        s.plate_number, 
        d.username AS driver_name
    FROM shuttles s
    LEFT JOIN shuttle_assignments sa ON sa.shuttle_id = s.id
    LEFT JOIN driver_accounts d ON d.id = sa.driver_id
    WHERE s.status='Active'
    ORDER BY s.shuttle_name
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Live Shuttle Tracker</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<style>
#map { height: 400px; border-radius: 8px; margin-bottom: 20px; }
body { padding: 20px; background: #f0f8ff; }
.section-box { background: #e6f0ff; padding: 15px; border-radius: 8px; }
</style>
</head>
<body>

<h2 class="text-center text-primary mb-4">🚍 Live Shuttle Tracking</h2>

<select id="shuttleSelect" class="form-select mb-4">
    <option value="">--Select Shuttle--</option>
    <?php while ($s = $shuttles->fetch_assoc()): ?>
        <option value="<?= $s['shuttle_id'] ?>" data-driver="<?= htmlspecialchars($s['driver_name'] ?? 'Unassigned') ?>">
            <?= $s['shuttle_name'] ?> (<?= $s['plate_number'] ?>)
        </option>
    <?php endwhile; ?>
</select>

<div class="row">
    <div class="col-md-8"><div id="map"></div></div>
    <div class="col-md-4">
        <div class="section-box">
            <p id="status" class="fw-bold text-primary">Select a shuttle to start</p>
            <p>Latitude: <span id="lat">--</span></p>
            <p>Longitude: <span id="lng">--</span></p>
            <p>Last Updated: <span id="lastUpdate">--</span></p>
            <p>Place: <span id="place">--</span></p>
        </div>
        <div class="section-box mt-3">
            <h5>👨‍✈️ Driver Info</h5>
            <p id="driver">Driver: --</p>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
let map = L.map('map').setView([14.5995, 120.9842], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

let marker = null;
let interval = null;

const latEl = document.getElementById("lat");
const lngEl = document.getElementById("lng");
const placeEl = document.getElementById("place");
const lastUpdateEl = document.getElementById("lastUpdate");
const driverEl = document.getElementById("driver");
const statusEl = document.getElementById("status");
const select = document.getElementById("shuttleSelect");

// Fetch latest location for a selected shuttle
async function fetchLocation(shuttleId) {
    try {
        const res = await fetch(`fetch_location.php?shuttle_id=${shuttleId}`);
        const data = await res.json();

        if (data.status !== "success") {
            statusEl.textContent = `No active location for this shuttle`;
            return;
        }

        const lat = parseFloat(data.latitude);
        const lng = parseFloat(data.longitude);

        // Add or update marker
        if (marker) marker.setLatLng([lat, lng]);
        else marker = L.marker([lat, lng]).addTo(map);

        map.setView([lat, lng], 16);

        // Update info panel
        latEl.textContent = lat.toFixed(5);
        lngEl.textContent = lng.toFixed(5);
        lastUpdateEl.textContent = data.updated_at;
        driverEl.textContent = "Driver: " + (data.driver_name || "Unassigned");
        statusEl.textContent = `🚍 ${select.selectedOptions[0].textContent} live info`;

        // Reverse geocode for place name
        try {
            const r = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
            const loc = await r.json();
            placeEl.textContent = loc.display_name || "--";
        } catch {
            placeEl.textContent = "--";
        }

    } catch (err) {
        console.error(err);
        statusEl.textContent = "Error fetching shuttle location";
    }
}

// Shuttle select event
select.addEventListener("change", () => {
    const shuttleId = select.value;
    if (!shuttleId) return;

    if (interval) clearInterval(interval);
    fetchLocation(shuttleId);
    interval = setInterval(() => fetchLocation(shuttleId), 3000);
});
</script>

</body>
</html>