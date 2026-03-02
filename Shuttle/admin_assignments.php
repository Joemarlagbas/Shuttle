<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli("localhost", "root", "", "shuttle_db");
$conn->set_charset("utf8mb4");

$msg = "";
$type = "";

/* ===== ASSIGN DRIVER ===== */
if (isset($_POST['assign'])) {
    $shuttle = intval($_POST['shuttle']);
    $driver  = intval($_POST['driver']);

    // Check if shuttle or driver is already assigned
    $check = $conn->prepare("
        SELECT id FROM shuttle_assignments 
        WHERE shuttle_id=? OR driver_id=?
    ");
    $check->bind_param("ii", $shuttle, $driver);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $msg = "❌ Shuttle or Driver already assigned!";
        $type = "danger";
    } else {
        // Insert assignment
        $stmt = $conn->prepare("
            INSERT INTO shuttle_assignments (shuttle_id, driver_id)
            VALUES (?, ?)
        ");
        $stmt->bind_param("ii", $shuttle, $driver);
        $stmt->execute();

        $msg = "✅ Assignment successful!";
        $type = "success";

        // Reset POST to avoid duplicate submission
        $_POST = [];
    }
}

/* ===== DELETE ASSIGNMENT ===== */
if (isset($_GET['del'])) {
    $delId = intval($_GET['del']);
    $stmt = $conn->prepare("DELETE FROM shuttle_assignments WHERE id=?");
    $stmt->bind_param("i", $delId);
    $stmt->execute();

    header("Location: admin_assignments.php");
    exit;
}

/* ===== FETCH AVAILABLE SHUTTLES & DRIVERS ===== */
$shuttles = $conn->query("
    SELECT * FROM shuttles
    WHERE id NOT IN (SELECT shuttle_id FROM shuttle_assignments)
");

$drivers = $conn->query("
    SELECT * FROM driver_accounts
    WHERE id NOT IN (SELECT driver_id FROM shuttle_assignments)
");

/* ===== FETCH CURRENT ASSIGNMENTS ===== */
$assignments = $conn->query("
    SELECT sa.id, s.shuttle_name, s.plate_number, d.driver_name
    FROM shuttle_assignments sa
    JOIN shuttles s ON sa.shuttle_id = s.id
    JOIN driver_accounts d ON sa.driver_id = d.id
    ORDER BY sa.id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin | Shuttle Assignment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'admin_navbar.php'; ?>

<div class="container mt-4">

    <h3>Assign Driver to Shuttle</h3>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $type ?>"><?= $msg ?></div>
    <?php endif; ?>

    <form method="POST" class="row g-3 mb-4">

        <div class="col-md-5">
            <label class="form-label">Select Shuttle</label>
            <select name="shuttle" class="form-control" required>
                <option value="">-- Select Shuttle --</option>
                <?php while ($s = $shuttles->fetch_assoc()): ?>
                    <option value="<?= $s['id'] ?>">
                        <?= $s['shuttle_name'] ?> (<?= $s['plate_number'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-5">
            <label class="form-label">Select Driver</label>
            <select name="driver" class="form-control" required>
                <option value="">-- Select Driver --</option>
                <?php while ($d = $drivers->fetch_assoc()): ?>
                    <option value="<?= $d['id'] ?>"><?= $d['driver_name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button name="assign" class="btn btn-primary w-100">Assign</button>
        </div>

    </form>

    <hr>

    <h4>Current Assignments</h4>

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-primary">
            <tr>
                <th>Shuttle</th>
                <th>Plate</th>
                <th>Driver</th>
                <th width="120">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($assignments->num_rows == 0): ?>
                <tr>
                    <td colspan="4" class="text-center text-muted">No assignments yet</td>
                </tr>
            <?php else: ?>
                <?php while ($a = $assignments->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($a['shuttle_name']) ?></td>
                        <td><?= htmlspecialchars($a['plate_number']) ?></td>
                        <td><?= htmlspecialchars($a['driver_name']) ?></td>
                        <td>
                            <a href="?del=<?= $a['id'] ?>"
                               onclick="return confirm('Remove assignment?')"
                               class="btn btn-danger btn-sm">Remove</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>

</div>
</body>
</html>