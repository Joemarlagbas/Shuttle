<?php
$conn = new mysqli("localhost", "root", "", "shuttle_db");
if ($conn->connect_error) die("DB Error");

$id = $_GET['id'];
$conn->query("DELETE FROM schedule WHERE id=$id");

header("Location: admin_schedule.php");
exit;
