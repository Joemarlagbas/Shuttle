<?php
$conn = new mysqli("localhost", "root", "", "shuttle_db");
if ($conn->connect_error) die("DB Error");

$id      = $_POST['id'] ?? '';
$title   = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';

if ($id == '') {
  die("Invalid request");
}

/* ===== UPDATE CONTENT ===== */
$stmt = $conn->prepare("UPDATE site_content SET title=?, content=? WHERE id=?");
$stmt->bind_param("ssi", $title, $content, $id);
$stmt->execute();

/* ===== IMAGE UPLOAD ===== */
if (!empty($_FILES['image']['name'])) {

  $sectionStmt = $conn->prepare("SELECT section FROM site_content WHERE id=?");
  $sectionStmt->bind_param("i", $id);
  $sectionStmt->execute();
  $section = $sectionStmt->get_result()->fetch_assoc()['section'];

  $uploadDir = __DIR__ . "/uploads/";
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }

  $fileName = time() . "_" . basename($_FILES['image']['name']);
  $target   = $uploadDir . $fileName;

  if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {

    $check = $conn->prepare("SELECT id FROM site_images WHERE section=?");
    $check->bind_param("s", $section);
    $check->execute();

    if ($check->get_result()->num_rows > 0) {
      $up = $conn->prepare("UPDATE site_images SET image=? WHERE section=?");
      $up->bind_param("ss", $fileName, $section);
      $up->execute();
    } else {
      $in = $conn->prepare("INSERT INTO site_images(section,image) VALUES (?,?)");
      $in->bind_param("ss", $section, $fileName);
      $in->execute();
    }
  }
}

header("Location: admin_landing.php?success=1");
exit;
