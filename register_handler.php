<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    $errors = [];

    // Validate required fields
    if (empty($name)) $errors[] = "Name is required.";
    if (empty($username)) $errors[] = "Username is required.";
    if (empty($password)) $errors[] = "Password is required.";
    if (empty($confirm_password)) $errors[] = "Confirm Password is required.";

    // Validate password matching
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check for errors before proceeding
    if (!empty($errors)) {
        die("Error: " . implode(", ", $errors));
    }

    // Check for duplicate username
    $check_sql = "SELECT 1 FROM users WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);

    if (!$check_stmt) {
        die("SQL Error: " . $conn->error);
    }

    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        die("Error: An account with this username already exists!");
    }

    // Hash the password securely using password_hash
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user
    $sql = "INSERT INTO users (name, username, phone, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("ssss", $name, $username, $phone, $hashed_password);

    if ($stmt->execute()) {
        header("Location: success.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $check_stmt->close();
}

$conn->close();
?>
