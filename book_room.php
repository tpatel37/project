<?php
session_start();
include 'db_config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['room_no'])) {
    $room_no = intval($_GET['room_no']); // Sanitize input

    // Fetch room details from the database
    $sql = "SELECT * FROM rooms WHERE room_no = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $room_no);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $room = $result->fetch_assoc();
            $room['price_per_night'] = is_numeric($room['price_per_night']) ? $room['price_per_night'] : 0; // Ensure valid price
        } else {
            echo "<p class='text-center text-danger'>Room not found or not available.</p>";
            exit();
        }
    } else {
        die("SQL Error: " . $conn->error);
    }
} else {
    echo "<p class='text-center text-danger'>No room selected.</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container my-5">
        <h2 class="text-center">Book Your Room</h2>
        <div class="card mx-auto" style="max-width: 600px;">
            <img src="<?php echo htmlspecialchars($room['image_path']); ?>" class="card-img-top" alt="Room Image">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($room['type']); ?></h5>
                <p class="card-text"><strong>Price per night:</strong> $<?php echo htmlspecialchars($room['price_per_night']); ?></p>
                <p class="card-text"><strong>Room Number:</strong> <?php echo htmlspecialchars($room['room_no']); ?></p>
                <form action="confirm_booking.php" method="POST">
                    <input type="hidden" name="room_no" value="<?php echo htmlspecialchars($room['room_no']); ?>">

                    <!-- Check-in Date -->
                    <div class="mb-3">
                        <label for="check_in" class="form-label">Check-in Date</label>
                        <input type="text" id="check_in" name="check_in" class="form-control" required>
                    </div>

                    <!-- Check-out Date -->
                    <div class="mb-3">
                        <label for="check_out" class="form-label">Check-out Date</label>
                        <input type="text" id="check_out" name="check_out" class="form-control" required>
                    </div>

                    <!-- Total Price -->
                    <div class="mb-3">
                        <label for="total_price" class="form-label">Total Price</label>
                        <input type="text" id="total_price" name="total_price" class="form-control" readonly>
                    </div>

                    <button type="submit" class="btn btn-success">Confirm Booking</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const today = new Date().toISOString().split('T')[0];
        const checkInField = document.getElementById('check_in');
        const checkOutField = document.getElementById('check_out');
        const totalPriceField = document.getElementById('total_price');
        const pricePerNight = <?php echo $room['price_per_night']; ?>;

        checkInField.setAttribute('min', today);
        checkOutField.setAttribute('min', today);

        function calculateTotalPrice() {
            const checkInValue = checkInField.value;
            const checkOutValue = checkOutField.value;

            if (!checkInValue) {
                alert("Please select a check-in date first.");
                checkOutField.value = "";
                totalPriceField.value = "";
                return;
            }

            const checkInDate = new Date(checkInValue);
            const checkOutDate = new Date(checkOutValue);

            if (checkOutDate <= checkInDate) {
                alert("Check-out date must be after the check-in date.");
                checkOutField.value = "";
                totalPriceField.value = "";
            } else {
                const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
                totalPriceField.value = nights * pricePerNight;
            }
        }

        checkInField.addEventListener('change', function () {
            checkOutField.setAttribute('min', checkInField.value);
        });
        checkOutField.addEventListener('input', calculateTotalPrice);
        checkOutField.addEventListener('change', calculateTotalPrice);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            flatpickr("#check_in", { minDate: "today", dateFormat: "Y-m-d" });
            flatpickr("#check_out", { minDate: "today", dateFormat: "Y-m-d" });
        });
    </script>
</body>
</html>
