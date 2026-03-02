<?php
$conn = new mysqli("localhost", "root", "", "shuttle_db");
if ($conn->connect_error) die("DB Error");

$msg = "";
$type = "";

/* ===== FETCH ALL DRIVERS ===== */
$drivers = $conn->query("SELECT * FROM driver_accounts ORDER BY username ASC");

/* ===== APPROVE / DISAPPROVE / DELETE ===== */
if(isset($_GET['approve'])){
    $id = intval($_GET['approve']);
    $conn->query("UPDATE driver_accounts SET approved=1 WHERE id=$id");
    $msg = "Driver approved successfully!";
    $type = "success";
}

if(isset($_GET['disapprove'])){
    $id = intval($_GET['disapprove']);
    $conn->query("UPDATE driver_accounts SET approved=0 WHERE id=$id");
    $msg = "Driver disapproved successfully!";
    $type = "warning";
}

if(isset($_GET['del'])){
    $id = intval($_GET['del']);
    $conn->query("DELETE FROM driver_accounts WHERE id=$id");
    $conn->query("DELETE FROM shuttle_assignments WHERE driver_id=$id");
    $msg = "Driver deleted successfully!";
    $type = "danger";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin | Drivers</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
body {
    background-color: #f4f6f9;
    font-family:'Segoe UI',sans-serif;
    padding: 30px;
}

.card-glass {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    padding: 25px;
}

.card-glass h3 {
    text-align: center;
    margin-bottom: 25px;
}

.table th, .table td {
    vertical-align: middle;
}

.badge-success {background: #28a745;}
.badge-warning {background: #ffc107; color: #212529;}
.badge-danger {background: #dc3545;}

.btn-simple {
    background-color: #007bff;
    color: white;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    padding: 5px 10px;
    transition: 0.3s;
    text-decoration: none;
}
.btn-simple:hover {background-color: #0056b3; color:white;}
.btn-delete {background-color:#dc3545;}
.btn-delete:hover {background-color:#a71d2a; color:white;}
</style>
</head>
<body>

<?php include 'admin_navbar.php'; ?>

<div class="container">
    <div class="card-glass">
        <h3>Driver Management</h3>

        <?php if($msg): ?>
            <div class="alert alert-<?= $type ?>"><?= $msg ?></div>
        <?php endif; ?>

        <table class="table table-bordered align-middle">
            <tr class="table-light">
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th width="250">Actions</th>
            </tr>

            <?php if($drivers->num_rows==0): ?>
                <tr><td colspan="4" class="text-center text-muted">No drivers found</td></tr>
            <?php endif; ?>

            <?php while($d = $drivers->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($d['username']) ?></td>
                    <td><?= htmlspecialchars($d['password']) ?></td>
                    <td>
                        <?php if($d['approved']==1): ?>
                            <span class="badge badge-success">Approved</span>
                        <?php else: ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($d['approved']==0): ?>
                            <a href="?approve=<?= $d['id'] ?>" class="btn btn-simple btn-sm">Approve</a>
                        <?php else: ?>
                            <a href="?disapprove=<?= $d['id'] ?>" class="btn btn-simple btn-sm">Disapprove</a>
                        <?php endif; ?>
                        <a href="?del=<?= $d['id'] ?>" onclick="return confirm('Delete driver?')" class="btn btn-simple btn-sm btn-delete">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

</body>
</html>