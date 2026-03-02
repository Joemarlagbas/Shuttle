<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost","root","","shuttle_db");
if ($conn->connect_error) { http_response_code(500); exit; }

$data = json_decode(file_get_contents("php://input"), true);
$driver_id = intval($data['driver_id'] ?? 0);
$status = intval($data['status'] ?? 0);

$stmt = $conn->prepare("UPDATE driver_accounts SET tracking_status=? WHERE id=?");
$stmt->bind_param("ii",$status,$driver_id);
$stmt->execute();
$stmt->close();
$conn->close();

echo json_encode(["status"=>"success"]);