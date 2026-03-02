<?php
$conn = new mysqli("localhost", "root", "", "shuttle_db");
if ($conn->connect_error) die("DB Error");

$stmt = $conn->prepare(
  "UPDATE schedule
   SET route=?, first_trip=?, last_trip=?, frequency=?
   WHERE id=?"
);
$stmt->bind_param(
  "ssssi",
  $_POST['route'],
  $_POST['first_trip'],
  $_POST['last_trip'],
  $_POST['frequency'],
  $_POST['id']
);
$stmt->execute();

header("Location: admin_schedule.php");
exit;
