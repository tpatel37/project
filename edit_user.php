<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $user = $conn->query("SELECT * FROM users WHERE id = $userId")->fetch_assoc();

    if (!$user) {
        die("User not found.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE users SET name = ?, username = ?, phone = ? WHERE id = ?");
    $stmt->bind_param('sssi', $name, $username, $phone, $userId);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        die("Error updating user: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>
    <form method="post">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        <label>Username:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        <label>Phone:</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
        <button type="submit">Update User</button>
    </form>
</body>
</html>
