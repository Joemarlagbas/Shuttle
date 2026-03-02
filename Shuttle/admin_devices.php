<?php
$conn = new mysqli("localhost", "root", "", "shuttle_db");

if (isset($_POST['add'])) {
    $shuttle_id = intval($_POST['shuttle']);
    $device_name = $_POST['device_name'];
    $api_key = bin2hex(random_bytes(16)); // generate random 32-char key
    $stmt = $conn->prepare("INSERT INTO device_keys (shuttle_id, device_name, api_key) VALUES (?,?,?)");
    $stmt->bind_param("iss", $shuttle_id, $device_name, $api_key);
    $stmt->execute();
}
$devices = $conn->query("SELECT d.*, s.shuttle_name FROM device_keys d JOIN shuttles s ON s.id=d.shuttle_id");
$shuttles = $conn->query("SELECT * FROM shuttles");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin | Devices</title>
</head>

<body>
    <h3>Add Device</h3>
    <form method="POST">
        <select name="shuttle">
            <option value="">-- Select Shuttle --</option>
            <?php while ($s = $shuttles->fetch_assoc()): ?>
                <option value="<?= $s['id'] ?>"><?= $s['shuttle_name'] ?> (<?= $s['plate_number'] ?>)</option>
            <?php endwhile; ?>
        </select>
        <input type="text" name="device_name" placeholder="Device Name" required>
        <button type="submit" name="add">Add Device</button>
    </form>

    <h4>Existing Devices</h4>
    <table border=1>
        <tr>
            <th>Shuttle</th>
            <th>Device Name</th>
            <th>API Key</th>
        </tr>
        <?php while ($d = $devices->fetch_assoc()): ?>
            <tr>
                <td><?= $d['shuttle_name'] ?></td>
                <td><?= $d['device_name'] ?></td>
                <td><?= $d['api_key'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>