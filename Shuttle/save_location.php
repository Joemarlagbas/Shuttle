<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "shuttle_db");
if ($conn->connect_error) { 
    http_response_code(500); 
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$driver_id = intval($data['driver_id'] ?? 0);
$shuttle_id = intval($data['shuttle_id'] ?? 0);
$lat = floatval($data['lat'] ?? 0);
$lng = floatval($data['lng'] ?? 0);

// Check for valid input data
if (!$driver_id || !$shuttle_id || $lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit;
}

// Check if the driver is currently tracking
$check = $conn->prepare("SELECT tracking_status FROM driver_accounts WHERE id=?");
$check->bind_param("i", $driver_id);
$check->execute();
$stat = $check->get_result()->fetch_assoc();

if (!$stat || $stat['tracking_status'] != 1) {
    echo json_encode(["status" => "error", "message" => "Driver not tracking"]);
    exit;
}

// Insert or update the location in the database
$stmt = $conn->prepare("
    INSERT INTO location (shuttle_id, driver_id, latitude, longitude, updated_at)
    VALUES (?, ?, ?, ?, NOW())
    ON DUPLICATE KEY UPDATE latitude = VALUES(latitude), longitude = VALUES(longitude), updated_at = NOW()
");

$stmt->bind_param("iidd", $shuttle_id, $driver_id, $lat, $lng);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database insert failed"]);
}

$stmt->close();
$conn->close();
?>