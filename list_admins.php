
<?php
include 'db_config.php';

$sql = "SELECT * FROM admins";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Existing Admins:<br>";
    while ($row = $result->fetch_assoc()) {
        echo "Username: " . $row['username'] . "<br>";
    }
} else {
    echo "No admins found in the database.";
}

$conn->close();
?>
