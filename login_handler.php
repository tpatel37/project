<?php
session_start();
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Trim and sanitize input data
    $username = trim($_POST['username']) ?? null;
    $password = trim($_POST['password']) ?? null;

    // Validate input
    if (empty($username) || empty($password)) {
        die("Username and Password are required!");
    }

    // Prepare SQL query to fetch user by username
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $stored_password = $user['password'];

        // Determine hashing method
        if (password_verify($password, $stored_password)) {
            // Bcrypt password verified
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: dashboard.php");
            exit();
        } elseif (hash('sha256', $password) === $stored_password) {
            // SHA-256 password verified
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: dashboard.php");
            exit();
        } else {
            die("Invalid password!");
        }
    } else {
        die("No user found with this username!");
    }

    $stmt->close();
}

$conn->close();
?>
