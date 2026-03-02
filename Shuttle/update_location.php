<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "shuttle_db");
if ($conn->connect_error) {
    http_response_code(500);
    exit(json_encode(["status"=>"error","message"=>"DB connection failed"]));
}

// get POST values
$api_key = $_POST['api_key'] ?? '';
$shuttle_id = intval($_POST['shuttle_id'] ?? 0);
$lat = floatval($_POST['lat'] ?? 0);
$lng = floatval($_POST['lng'] ?? 0);

// check api_key against device_keys
$stmt = $conn->prepare("SELECT * FROM device_keys WHERE shuttle_id=? AND api_key=? LIMIT 1");
$stmt->bind_param("is", $shuttle_id, $api_key);
$stmt->execute();
$res = $stmt->get_result();
if($res->num_rows == 0){
    http_response_code(403);
    exit(json_encode(["status"=>"error","message"=>"Unauthorized device"]));
}

// validate lat/lng
if (!$shuttle_id || $lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
    http_response_code(400);
    exit(json_encode(["status"=>"error","message"=>"Invalid input"]));
}

// insert or update location
$stmt2 = $conn->prepare("
    INSERT INTO location (shuttle_id, latitude, longitude, updated_at)
    VALUES (?,?,?,NOW())
    ON DUPLICATE KEY UPDATE
        latitude=VALUES(latitude),
        longitude=VALUES(longitude),
        updated_at=NOW()
");
$stmt2->bind_param("idd", $shuttle_id, $lat, $lng);
if ($stmt2->execute()) echo json_encode(["status"=>"success","message"=>"Location updated"]);
else http_response_code(500);
$stmt2->close();
$conn->close();
?>