<?php
include 'db_config.php'; // Include database configuration

session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $room_no = intval($_POST['room_no']);
    $type = $_POST['type'];
    $price_per_night = floatval($_POST['price_per_night']);
    $status = $_POST['status'];

    // Validate form inputs
    if (empty($room_no) || empty($type) || $price_per_night <= 0 || empty($status)) {
        $error_message = "All fields are required and must be valid.";
    } else {
        // Handle file upload
        $target_dir = "images/rooms/"; // Folder to store room images
        $file_name = basename($_FILES["room_image"]["name"]);
        $target_file = $target_dir . uniqid() . "_" . $file_name; // Unique file name
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an image
        $check = getimagesize($_FILES["room_image"]["tmp_name"]);
        if ($check === false) {
            $error_message = "File is not an image.";
        } elseif (!in_array($image_file_type, ["jpg", "jpeg", "png", "gif"])) {
            $error_message = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        } elseif ($_FILES["room_image"]["size"] > 5000000) { // Limit file size to 5MB
            $error_message = "File size should not exceed 5MB.";
        } elseif (!move_uploaded_file($_FILES["room_image"]["tmp_name"], $target_file)) {
            $error_message = "There was an error uploading the file.";
        } else {
            // Insert the new room into the database
            $sql = "INSERT INTO rooms (room_no, type, price_per_night, status, image_path) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("isdss", $room_no, $type, $price_per_night, $status, $target_file);
                if ($stmt->execute()) {
                    $success_message = "Room added successfully!";
                } else {
                    $error_message = "Error adding room: " . $stmt->error;
                }
            } else {
                $error_message = "Error preparing the statement: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container my-5">
        <h2 class="text-center">Add New Room</h2>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php elseif (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="my-4">
            <div class="mb-3">
                <label for="room_no" class="form-label">Room Number</label>
                <input type="number" id="room_no" name="room_no" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Room Type</label>
                <input type="text" id="type" name="type" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="price_per_night" class="form-label">Price Per Night</label>
                <input type="number" step="0.01" id="price_per_night" name="price_per_night" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Availability Status</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="available">Available</option>
                    <option value="unavailable">Unavailable</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="room_image" class="form-label">Upload Room Image</label>
                <input type="file" id="room_image" name="room_image" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Room</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
