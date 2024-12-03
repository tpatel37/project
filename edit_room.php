<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin_login.php");
    exit();
}

include 'db_config.php';

if (isset($_GET['id'])) {
    $room_id = intval($_GET['id']);

    // Fetch room details
    $sql = "SELECT * FROM rooms WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $room = $result->fetch_assoc();
    } else {
        die("Room not found.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = intval($_POST['id']);
    $type = $_POST['type'];
    $price = floatval($_POST['price']);
    $status = $_POST['status'];

    $update_sql = "UPDATE rooms SET type = ?, price_per_night = ?, status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sdsi", $type, $price, $status, $room_id);

    if ($update_stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        die("Error updating room: " . $update_stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Room</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $room['id']; ?>">
            <div class="mb-3">
                <label for="type" class="form-label">Room Type</label>
                <input type="text" id="type" name="type" class="form-control" value="<?php echo htmlspecialchars($room['type']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price per Night</label>
                <input type="number" id="price" name="price" class="form-control" value="<?php echo htmlspecialchars($room['price_per_night']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="available" <?php echo $room['status'] === 'available' ? 'selected' : ''; ?>>Available</option>
                    <option value="booked" <?php echo $room['status'] === 'booked' ? 'selected' : ''; ?>>Booked</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Save Changes</button>
        </form>
    </div>
</body>
</html>
