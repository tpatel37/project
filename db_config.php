<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost"; // XAMPP default
$username = "root";        // Default username
$password = "";            // Default password (empty for XAMPP)
$dbname = "hotel_project"; // Your database name

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
