<?php
$conn = new mysqli("localhost", "root", "", "shuttle_db");
if ($conn->connect_error) die("DB Error");

$stmt = $conn->prepare(
  "INSERT INTO contact_messages(name,email,message)
   VALUES (?,?,?)"
);
$stmt->bind_param(
  "sss",
  $_POST['name'],
  $_POST['email'],
  $_POST['message']
);
$stmt->execute();
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Message Sent</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

  <div class="container text-center mt-5">
    <h3>Message Sent!</h3>
    <p>Thank you for contacting us. We’ll get back to you soon.</p>
    <a href="Landing.php" class="btn btn-primary mt-3">Back to Home</a>
  </div>

</body>

</html>