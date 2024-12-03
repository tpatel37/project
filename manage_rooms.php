<?php
session_start();
include 'db_config.php';

// Restrict access to admins
if (!isset($_SESSION['admin'])) {
    header("Location: index.php"); // Redirect to login if not logged in
    exit();
}

// Fetch rooms
$sql = "SELECT id, room_no, type, price_per_night, status FROM rooms";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching rooms: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Manage Rooms</h1>
        <a href="add_room.php" class="btn btn-primary mb-3">Add New Room</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Room No</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($room = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($room['room_no']); ?></td>
                        <td><?php echo htmlspecialchars($room['type']); ?></td>
                        <td><?php echo "$" . number_format($room['price_per_night'], 2); ?></td>
                        <td><?php echo htmlspecialchars($room['status']); ?></td>
                        <td>
                            <a href="edit_room.php?id=<?php echo $room['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete.php?id=<?php echo $room['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
