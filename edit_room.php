<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin_login.php");
    exit();
}

include 'db_config.php';

// Fetch room details for the given ID
if (isset($_GET['id'])) {
    $room_id = intval($_GET['id']);

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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = intval($_POST['id']);
    $type = $_POST['type'];
    $price = floatval($_POST['price']);
    $status = $_POST['status'];
    $delete_image = isset($_POST['delete_image']) ? true : false;

    // Handle image deletion
    if ($delete_image) {
        $query = "SELECT image_path FROM rooms WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $room_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $room = $result->fetch_assoc();

        if ($room && $room['image_path']) {
            $image_path = "images/Rooms/" . $room['image_path'];
            if (file_exists($image_path)) {
                unlink($image_path); // Remove image from filesystem
            }

            // Remove image path from the database
            $update_image_query = "UPDATE rooms SET image_path = NULL WHERE id = ?";
            $update_image_stmt = $conn->prepare($update_image_query);
            $update_image_stmt->bind_param('i', $room_id);
            $update_image_stmt->execute();
        }
    }

    // Handle image upload and resizing
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image'];

        if ($image['error'] === 0 && strpos($image['type'], 'image/') === 0) {
            $source_path = $image['tmp_name'];
            $target_path = 'images/Rooms/' . time() . '_' . basename($image['name']);

            // Define new dimensions
            $max_width = 800;
            $max_height = 600;

            // Get original dimensions
            list($original_width, $original_height) = getimagesize($source_path);

            // Calculate scaling ratio
            $ratio = min($max_width / $original_width, $max_height / $original_height);
            $new_width = $original_width * $ratio;
            $new_height = $original_height * $ratio;

            // Create a new true color image
            $new_image = imagecreatetruecolor($new_width, $new_height);

            // Load the original image
            if ($image['type'] === 'image/jpeg') {
                $original_image = imagecreatefromjpeg($source_path);
            } elseif ($image['type'] === 'image/png') {
                $original_image = imagecreatefrompng($source_path);
            } elseif ($image['type'] === 'image/gif') {
                $original_image = imagecreatefromgif($source_path);
            } else {
                die("Unsupported image type.");
            }

            // Resize the image
            imagecopyresampled(
                $new_image,
                $original_image,
                0, 0, 0, 0,
                $new_width,
                $new_height,
                $original_width,
                $original_height
            );

            // Save the resized image
            if ($image['type'] === 'image/jpeg') {
                imagejpeg($new_image, $target_path, 90);
            } elseif ($image['type'] === 'image/png') {
                imagepng($new_image, $target_path, 9);
            } elseif ($image['type'] === 'image/gif') {
                imagegif($new_image, $target_path);
            }

            // Update the database with the new image path
            $update_image_query = "UPDATE rooms SET image_path = ? WHERE id = ?";
            $update_image_stmt = $conn->prepare($update_image_query);
            $update_image_stmt->bind_param('si', $target_path, $room_id);
            $update_image_stmt->execute();
        }
    }

    // Update room details
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
        <form method="POST" action="" enctype="multipart/form-data">
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
            <div class="mb-3">
                <label for="current_image" class="form-label">Current Image</label><br>
                <?php if (!empty($room['image_path'])): ?>
                    <img src="images/Rooms/<?php echo htmlspecialchars($room['image_path']); ?>" alt="Room Image" width="150">
                    <label>
                        <input type="checkbox" name="delete_image" value="1"> Delete this image
                    </label>
                <?php else: ?>
                    <p>No image available</p>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Upload New Image</label>
                <input type="file" id="image" name="image" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn btn-success">Save Changes</button>
        </form>
    </div>
</body>
</html>
