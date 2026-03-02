<?php
// =====================
// SEND SHUTTLE LOCATION
// =====================

$conn = new mysqli("localhost","root","","shuttle_db");
if($conn->connect_error) die("DB Error");

// === 1. Authenticate driver ===
// In real setup, replace this with SESSION login
$driver_id = 1; // 🔴 replace with $_SESSION['driver_id'] in real use

// === 2. Get assigned shuttle for this driver ===
$q = $conn->prepare("
    SELECT shuttle_id 
    FROM shuttle_assignments
    WHERE driver_id=? AND status='Active'
    LIMIT 1
");
$q->bind_param("i", $driver_id);
$q->execute();
$res = $q->get_result()->fetch_assoc();

if(!$res){
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'No active shuttle assigned.']);
    exit;
}

$shuttle_id = $res['shuttle_id'];

// === 3. Validate incoming POST data ===
if(!isset($_POST['lat'], $_POST['lng'])){
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Latitude and Longitude required.']);
    exit;
}

$lat = floatval($_POST['lat']);
$lng = floatval($_POST['lng']);

// === 4. Insert or update location ===
// Make sure 'shuttle_id' is PRIMARY or UNIQUE key in location table
$stmt = $conn->prepare("
    INSERT INTO location (shuttle_id, latitude, longitude, updated_at)
    VALUES (?, ?, ?, NOW())
    ON DUPLICATE KEY UPDATE
        latitude=VALUES(latitude),
        longitude=VALUES(longitude),
        updated_at=NOW()
");
$stmt->bind_param("idd", $shuttle_id, $lat, $lng);
if($stmt->execute()){
    echo json_encode(['status'=>'ok','message'=>'Location updated']);
} else {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'DB error: '.$stmt->error]);
}
