<?php
include 'db_config.php';

// username and password
$username = 'admin'; 
$password = 'hotel'; 

// Hash the password securely
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert the new admin into the database
$sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

$stmt->bind_param("ss", $username, $hashed_password);

if ($stmt->execute()) {
    echo "New admin account created successfully with username: $username";
} else {
    // Check if username already exists
    if ($conn->errno === 1062) {
        echo "Error: The username '$username' already exists.";
    } else {
        echo "Error: " . $stmt->error;
    }
}

$stmt->close();
$conn->close();
?>
