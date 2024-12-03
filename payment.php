<?php
include 'db_config.php';

if (isset($_GET['booking_id'])) {
    $booking_id = intval($_GET['booking_id']);

    // Fetch booking and user details
    $sql = "SELECT b.id AS booking_id, b.total_price, u.name 
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            WHERE b.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();
    } else {
        die("Booking not found.");
    }

    // Process payment on form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $payment_date = date('Y-m-d'); // Current date
        $payment_method = 'cash'; // Fixed to 'cash'
        $amount = $booking['total_price'];
        $status = 'paid'; // Mark as paid

        // Insert payment record
        $insert_sql = "INSERT INTO payments (booking_id, payment_date, payment_method, amount, status) 
                       VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("issds", $booking_id, $payment_date, $payment_method, $amount, $status);

        if ($insert_stmt->execute()) {
            // Redirect to thank_you.php with the user's name
            header("Location: thank_you.php?name=" . urlencode($booking['name']));
            exit();
        } else {
            die("Payment processing failed: " . $insert_stmt->error);
        }
    }
} else {
    die("No booking ID provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center">Payment Details</h2>
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <h5 class="card-title">Booking Payment</h5>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($booking['name']); ?></p>
                <p><strong>Amount:</strong> $<?php echo htmlspecialchars(number_format($booking['total_price'], 2)); ?></p>
                <p><strong>Payment Method:</strong> Cash</p>
                <form method="POST">
                    <button type="submit" class="btn btn-success">Confirm Cash Payment</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
