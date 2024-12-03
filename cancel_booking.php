<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $bookingId = intval($_GET['id']);

    $stmt = $conn->prepare("UPDATE bookings SET status = 'canceled' WHERE id = ?");
    $stmt->bind_param('i', $bookingId);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        die("Error canceling booking: " . $conn->error);
    }
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>
