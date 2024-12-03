<?php
session_start();
include 'db_config.php';

// Restrict access to admins
if (!isset($_SESSION['admin'])) {
    echo "Access denied. Admin session not set.";
    exit();
}

// Validate the room ID
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    echo "Invalid room ID!";
    exit();
}

// Delete room
$sql = "DELETE FROM rooms WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "SQL Error: " . $conn->error;
    exit();
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Room deleted successfully!";
} else {
    echo "SQL Execution Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
header("Location: admin_dashboard.php");
exit();
?>
