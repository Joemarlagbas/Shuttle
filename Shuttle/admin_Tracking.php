<?php
$conn = new mysqli("localhost", "root", "", "shuttle_db");
if ($conn->connect_error) die("DB Error");

$locations = $conn->query("
    SELECT l.*, s.shuttle_name
    FROM location l LEFT
    JOIN shuttles s ON l.shuttle_id = s.id
    ORDER BY l.updated_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Shuttle Tracking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .content {
            width: 100%;
        }
    </style>
</head>

<body>

<?php include 'admin_navbar.php'; ?>

<div class="content p-4">

    <h3 class="mb-4">Shuttle Locations</h3>

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Shuttle</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($locations->num_rows > 0): ?>
                    <?php while ($l = $locations->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($l['shuttle_name']) ?></td>
                            <td><?= htmlspecialchars($l['latitude']) ?></td>
                            <td><?= htmlspecialchars($l['longitude']) ?></td>
                            <td><?= date('Y-m-d H:i:s', strtotime($l['updated_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No location data available
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>

</div> <!-- content -->
</div> <!-- d-flex from sidebar -->

</body>
</html>
