<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param('i', $userId);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        die("Error deleting user: " . $conn->error);
    }
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>
