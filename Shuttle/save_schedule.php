<?php
$conn = new mysqli("localhost", "root", "", "shuttle_db");
if ($conn->connect_error) die("DB Error");

$stmt = $conn->prepare(
  "INSERT INTO schedule(route,first_trip,last_trip,frequency)
   VALUES (?,?,?,?)"
);
$stmt->bind_param(
  "ssss",
  $_POST['route'],
  $_POST['first_trip'],
  $_POST['last_trip'],
  $_POST['frequency']
);
$stmt->execute();

header("Location: admin_schedule.php");
exit;
