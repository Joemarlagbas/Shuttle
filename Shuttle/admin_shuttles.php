<?php
$conn = new mysqli("localhost", "root", "", "shuttle_db");
if ($conn->connect_error) die("DB Error");

$msg = "";
$type = "";

/* ===== FETCH ALL DRIVERS ===== */
$drivers = $conn->query("SELECT id, username FROM driver_accounts WHERE approved=1 ORDER BY username");

/* ===== ADD SHUTTLE ===== */
if (isset($_POST['add'])) {
    $name = $_POST['name'] ?? '';
    $plate = $_POST['plate'] ?? '';
    $status = $_POST['status'] ?? 'Active';
    $driver_id = $_POST['driver_id'] ?? null;

    if ($name && $plate) {
        $stmt = $conn->prepare("INSERT INTO shuttles (shuttle_name, plate_number, status) VALUES (?,?,?)");
        $stmt->bind_param("sss", $name, $plate, $status);
        $stmt->execute();
        $shuttle_id = $stmt->insert_id;

        // Assign driver if selected
        if ($driver_id) {
            $assign_stmt = $conn->prepare("INSERT INTO shuttle_assignments (shuttle_id, driver_id) VALUES (?,?)");
            $assign_stmt->bind_param("ii", $shuttle_id, $driver_id);
            $assign_stmt->execute();
        }

        $msg = "Shuttle added successfully!";
        $type = "success";
    }
}

/* ===== UPDATE SHUTTLE ===== */
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = $_POST['name'] ?? '';
    $plate = $_POST['plate'] ?? '';
    $status = $_POST['status'] ?? 'Active';
    $driver_id = $_POST['driver_id'] ?? null;

    if ($id && $name && $plate) {
        $stmt = $conn->prepare("UPDATE shuttles SET shuttle_name=?, plate_number=?, status=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $plate, $status, $id);
        $stmt->execute();

        // Remove previous assignment
        $conn->query("DELETE FROM shuttle_assignments WHERE shuttle_id=$id");

        // Assign new driver if selected
        if ($driver_id) {
            $assign_stmt = $conn->prepare("INSERT INTO shuttle_assignments (shuttle_id, driver_id) VALUES (?,?)");
            $assign_stmt->bind_param("ii", $id, $driver_id);
            $assign_stmt->execute();
        }

        $msg = "Shuttle updated successfully!";
        $type = "success";
    }
}

/* ===== DELETE SHUTTLE ===== */
if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $conn->query("DELETE FROM shuttles WHERE id=$id");
    $conn->query("DELETE FROM shuttle_assignments WHERE shuttle_id=$id");
    header("Location: admin_shuttles.php");
    exit;
}

/* ===== FETCH ALL SHUTTLES WITH DRIVER INFO ===== */
$shuttles = $conn->query("
    SELECT s.*, sa.driver_id, d.username AS driver_name 
    FROM shuttles s
    LEFT JOIN shuttle_assignments sa ON sa.shuttle_id = s.id
    LEFT JOIN driver_accounts d ON d.id = sa.driver_id
    ORDER BY s.shuttle_name ASC
");

/* ===== EDIT MODE ===== */
$edit = null;
if (isset($_GET['edit'])) {
    $edit = $conn->query("SELECT * FROM shuttles WHERE id=" . intval($_GET['edit']))->fetch_assoc();

    $assigned_driver = $conn->query("SELECT driver_id FROM shuttle_assignments WHERE shuttle_id=" . intval($edit['id']))->fetch_assoc();
    $edit['driver_id'] = $assigned_driver['driver_id'] ?? null;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin | Shuttles</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'admin_navbar.php'; ?>
<div class="container mt-4">
    <h3><?= $edit ? "Edit Shuttle" : "Add Shuttle" ?></h3>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $type ?>"><?= $msg ?></div>
    <?php endif; ?>

    <form method="POST" class="row g-3 mb-4">
        <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

        <div class="col-md-3">
            <label class="form-label">Shuttle Name</label>
            <input type="text" name="name" class="form-control" required value="<?= $edit['shuttle_name'] ?? '' ?>">
        </div>

        <div class="col-md-3">
            <label class="form-label">Plate Number</label>
            <input type="text" name="plate" class="form-control" required value="<?= $edit['plate_number'] ?? '' ?>">
        </div>

        <div class="col-md-2">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="Active" <?= ($edit && $edit['status'] == "Active") ? "selected" : "" ?>>Active</option>
                <option value="Maintenance" <?= ($edit && $edit['status'] == "Maintenance") ? "selected" : "" ?>>Maintenance</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Assign Driver</label>
            <select name="driver_id" class="form-control">
                <option value="">-- Assign Driver --</option>
                <?php
                // Re-fetch drivers to ensure pointer is at start
                $drivers_result = $conn->query("SELECT id, username FROM driver_accounts WHERE approved=1 ORDER BY username");
                while ($d = $drivers_result->fetch_assoc()):
                ?>
                    <option value="<?= $d['id'] ?>" <?= ($edit && $edit['driver_id'] == $d['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($d['username']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-1 d-flex align-items-end">
            <button name="<?= $edit ? 'update' : 'add' ?>" class="btn btn-primary w-100"><?= $edit ? 'Update' : 'Add' ?></button>
        </div>

        <?php if ($edit): ?>
            <div class="col-md-12">
                <a href="admin_shuttles.php" class="btn btn-secondary">Cancel</a>
            </div>
        <?php endif; ?>
    </form>

    <hr>
    <h4>Shuttle List</h4>
    <table class="table table-bordered align-middle">
        <tr class="table-primary">
            <th>Name</th>
            <th>Plate</th>
            <th>Status</th>
            <th>Driver</th>
            <th width="160">Action</th>
        </tr>

        <?php if ($shuttles->num_rows == 0): ?>
            <tr>
                <td colspan="5" class="text-center text-muted">No shuttles found</td>
            </tr>
        <?php endif; ?>

        <?php while ($s = $shuttles->fetch_assoc()): ?>
            <tr>
                <td><?= $s['shuttle_name'] ?></td>
                <td><?= $s['plate_number'] ?></td>
                <td><span class="badge bg-<?= $s['status'] == "Active" ? "success" : "warning" ?>"><?= $s['status'] ?></span></td>
                <td><?= htmlspecialchars($s['driver_name'] ?? 'Unassigned') ?></td>
                <td>
                    <a href="?edit=<?= $s['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="?del=<?= $s['id'] ?>" onclick="return confirm('Delete?')" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>