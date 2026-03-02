<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "shuttle_db");
if ($conn->connect_error) {
    http_response_code(500);
    exit;
}

$shuttle_id = intval($_GET['shuttle_id'] ?? 0);
if (!$shuttle_id) {
    echo json_encode(["status" => "error", "message" => "Invalid shuttle ID"]);
    exit;
}

$stmt = $conn->prepare("
    SELECT l.latitude, l.longitude, l.updated_at, d.username AS driver_name
    FROM location l
    JOIN driver_accounts d ON d.id = l.driver_id
    WHERE l.shuttle_id = ? AND d.tracking_status = 1
    ORDER BY l.updated_at DESC LIMIT 1
");

$stmt->bind_param("i", $shuttle_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if ($row) {
    echo json_encode([
        "status" => "success",
        "latitude" => $row['latitude'],
        "longitude" => $row['longitude'],
        "updated_at" => $row['updated_at'],
        "driver_name" => $row['driver_name']
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "No active location"]);
}

$stmt->close();
$conn->close();
?>