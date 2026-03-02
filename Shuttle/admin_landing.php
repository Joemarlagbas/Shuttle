<?php

session_start();

  if(!isset($_SESSION['user'])) {
    header("Location: admin_login.php");
    exit;
  }

$conn = new mysqli("localhost", "root", "", "shuttle_db");
if ($conn->connect_error) die("DB Error");


function getContent($conn, $section) {
    $stmt = $conn->prepare("SELECT id, title, content FROM site_content WHERE section=? LIMIT 1");
    $stmt->bind_param("s", $section);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getImage($conn, $section) {
    $stmt = $conn->prepare("SELECT image FROM site_images WHERE section=? LIMIT 1");
    $stmt->bind_param("s", $section);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

$home     = getContent($conn, 'home');
$about    = getContent($conn, 'about');
$tracking = getContent($conn, 'tracking');

$homeImg  = getImage($conn, 'home');
$aboutImg = getImage($conn, 'about');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin | Landing Page</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f5f6fa;}
h3{font-weight:600;}
.card-header{background:#0072ff; color:white;}
.card{border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,0.1);}
img.section-img{border-radius:8px; margin-bottom:10px; max-width:100%;}
textarea.form-control{resize:none;}
</style>
</head>
<body>

<?php include 'admin_navbar.php'; ?>

<div class="container py-5">

    <h3 class="mb-4">Edit Landing Page</h3>

    <!-- HOME SECTION -->
    <div class="card mb-4">
        <div class="card-header">Home Section</div>
        <div class="card-body">
            <form action="save_landing.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $home['id'] ?>">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Title</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($home['title']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Content</label>
                    <textarea name="content" class="form-control" rows="4"><?= htmlspecialchars($home['content']) ?></textarea>
                </div>

                <button class="btn btn-primary">Save Home</button>
            </form>
        </div>
    </div>

    <!-- ABOUT SECTION -->
    <div class="card mb-4">
        <div class="card-header">About Section</div>
        <div class="card-body">
            <form action="save_landing.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $about['id'] ?>">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Title</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($about['title']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Content</label>
                    <textarea name="content" class="form-control" rows="5"><?= htmlspecialchars($about['content']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Image</label><br>
                    <?php if ($aboutImg && $aboutImg['image']): ?>
                        <img src="uploads/<?= htmlspecialchars($aboutImg['image']) ?>" class="section-img">
                    <?php endif; ?>
                    <input type="file" name="image" class="form-control">
                </div>

                <button class="btn btn-primary">Save About</button>
            </form>
        </div>
    </div>

</div>

</body>
</html>