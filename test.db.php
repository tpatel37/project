<?php
include 'db_config.php';

// Test query
$sql = "SHOW TABLES";
$result = $conn->query($sql);

if ($result) {
    echo "Connected successfully! Here are your tables: <br>";
    while ($row = $result->fetch_array()) {
        echo $row[0] . "<br>";
    }
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
