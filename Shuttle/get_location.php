<?php
$conn = new mysqli("localhost", "root", "", "shuttle_db");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

$query = "
    SELECT 
        s.id AS shuttle_id, 
        s.shuttle_name, 
        s.latitude, 
        s.longitude, 
        d.username AS driver_name
    FROM shuttles s
    LEFT JOIN shuttle_assignments sa ON sa.shuttle_id = s.id
    LEFT JOIN driver_accounts d ON d.id = sa.driver_id
    WHERE s.status = 'Active'
";

$result = $conn->query($query);
$shuttles = [];

while ($row = $result->fetch_assoc()) {
    $shuttles[] = [
        "shuttle_id" => $row['shuttle_id'],
        "shuttle_name" => $row['shuttle_name'],
        "latitude" => $row['latitude'],
        "longitude" => $row['longitude'],
        "driver_name" => $row['driver_name']
    ];
}

echo json_encode($shuttles);
$conn->close();
?>