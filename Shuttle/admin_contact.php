<?php
$conn = new mysqli("localhost", "root", "", "shuttle_db");
if ($conn->connect_error) die("DB Connection Error: " . $conn->connect_error);

// Fetch all contact submissions
$contacts = $conn->query("SELECT * FROM contact_messages ORDER BY created_at ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin | Contact Submissions</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <?php include 'admin_navbar.php'; ?>

    <div class="container mt-4">
        <h3>Contact Submissions</h3>

        <?php if ($contacts->num_rows == 0): ?>
            <p class="text-muted">No contact submissions yet.</p>
        <?php else: ?>
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $contacts->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                            <td><?= $row['created_at'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>
