<?php include 'admin_navbar.php'; ?>
<?php
$conn = new mysqli("localhost", "root", "", "shuttle_db");
if ($conn->connect_error) die("DB Error");

$schedules = $conn->query("SELECT * FROM schedule ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Admin | Shuttle Schedule</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

  <?php include "admin_navbar.php"; ?>

  <div class="container mt-4">

    <h3 class="mb-3">Manage Shuttle Schedule</h3>

    <!-- ADD NEW -->
    <div class="card mb-4">
      <div class="card-header fw-bold">Add New Schedule</div>
      <div class="card-body">
        <form action="save_schedule.php" method="POST">
          <div class="row">
            <div class="col-md-3">
              <input name="route" class="form-control" placeholder="Route" required>
            </div>
            <div class="col-md-2">
              <input type="time" name="first_trip" class="form-control" required>
            </div>
            <div class="col-md-2">
              <input type="time" name="last_trip" class="form-control" required>
            </div>
            <div class="col-md-3">
              <input name="frequency" class="form-control" placeholder="Frequency" required>
            </div>
            <div class="col-md-2">
              <button class="btn btn-primary w-100">Add</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- TABLE -->
    <div class="table-responsive shadow rounded">
      <table class="table table-bordered align-middle">
        <thead class="table-primary">
          <tr>
            <th>Route</th>
            <th>First Trip</th>
            <th>Last Trip</th>
            <th>Frequency</th>
            <th width="160">Action</th>
          </tr>
        </thead>
        <tbody>

          <?php while ($row = $schedules->fetch_assoc()): ?>
            <tr>
              <form action="update_schedule.php" method="POST">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">

                <td><input class="form-control" name="route" value="<?= htmlspecialchars($row['route']) ?>"></td>
                <td><input type="time" class="form-control" name="first_trip" value="<?= $row['first_trip'] ?>"></td>
                <td><input type="time" class="form-control" name="last_trip" value="<?= $row['last_trip'] ?>"></td>
                <td><input class="form-control" name="frequency" value="<?= htmlspecialchars($row['frequency']) ?>"></td>

                <td class="text-center">
                  <button class="btn btn-success btn-sm">Save</button>
                  <a href="delete_schedule.php?id=<?= $row['id'] ?>"
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('Delete this schedule?')">Delete</a>
                </td>
              </form>
            </tr>
          <?php endwhile; ?>

        </tbody>
      </table>
    </div>

  </div>
</body>

</html>