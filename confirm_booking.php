<?php
include 'db_config.php'; // Ensure this file correctly initializes $conn (the database connection)

session_start(); // Start the session to access session variables

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $room_no = intval($_POST['room_no']);
    $check_in_date = $_POST['check_in'];
    $check_out_date = $_POST['check_out'];
    $total_price = floatval($_POST['total_price']);
    
    // Use the logged-in user's ID from the session
    if (!isset($_SESSION['user_id'])) {
        die("User is not logged in.");
    }
    $user_id = $_SESSION['user_id']; // Retrieve user ID from the session
    $status = 'pending'; // Default status

    // Validate input data
    if (empty($check_in_date) || empty($check_out_date) || $total_price <= 0) {
        die("Invalid booking details.");
    }

    // Retrieve the room_id based on room_no
    $room_query = "SELECT id FROM rooms WHERE room_no = ? AND status = 'available'";
    $room_stmt = $conn->prepare($room_query);

    if (!$room_stmt) {
        die("Room query prepare failed: " . $conn->error);
    }

    $room_stmt->bind_param("i", $room_no);
    $room_stmt->execute();
    $room_result = $room_stmt->get_result();

    if ($room_result->num_rows === 0) {
        die("Room is not available.");
    }

    $room_data = $room_result->fetch_assoc();
    $room_id = $room_data['id'];

    // Insert booking into the bookings table
    $sql = "INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, total_price, status) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($sql);

    if (!$insert_stmt) {
        die("Insert query prepare failed: " . $conn->error);
    }

    $insert_stmt->bind_param("iissds", $user_id, $room_id, $check_in_date, $check_out_date, $total_price, $status);

    if ($insert_stmt->execute()) {
        // Get the last inserted booking ID
        $booking_id = $conn->insert_id;

        if ($booking_id) {
            // Redirect to payment page
            header("Location: payment.php?booking_id=" . $booking_id);
            exit();
        } else {
            die("Failed to retrieve the booking ID.");
        }
    } else {
        die("Error inserting booking: " . $insert_stmt->error);
    }
} else {
    die("Invalid request.");
}
?>
