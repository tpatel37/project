<?php 
include 'db_config.php';

// Default sorting
$order_by = "price_per_night";
$order_dir = "ASC";

// Check if the user has selected a sort option
if (isset($_GET['sort_by'])) {
    $sort_by = $_GET['sort_by'];
    switch ($sort_by) {
        case 'price_low_high':
            $order_by = "price_per_night";
            $order_dir = "ASC";
            break;
        case 'price_high_low':
            $order_by = "price_per_night";
            $order_dir = "DESC";
            break;
        case 'type':
            $order_by = "type";
            $order_dir = "ASC";
            break;
        case 'availability':
            $order_by = "status";
            $order_dir = "ASC";
            break;
    }
}

// Fetch rooms with sorting applied
$sql = "SELECT * FROM rooms ORDER BY $order_by $order_dir";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Rooms - Hotel Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .room-card img {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container-fluid bg-dark text-white text-center py-5 mb-4">
        <h1 class="display-4">Explore Our Rooms</h1>
        <p class="lead">Discover luxury, comfort, and convenience in every stay!</p>
    </div>

    <div class="container my-3">
        <!-- Sort and Currency Dropdown -->
        <form method="GET" class="d-flex justify-content-end align-items-center">
            <label for="currency" class="me-2">Currency:</label>
            <select id="currency" class="form-select w-auto" onchange="updateRoomPrices()">
                <option value="USD" selected>USD</option>
                <option value="EUR">EUR</option>
                <option value="GBP">GBP</option>
                <option value="INR">INR</option>
                <option value="CAD">CAD</option>
            </select>

            <label for="sort_by" class="me-2 ms-4">Sort By:</label>
            <select id="sort_by" name="sort_by" class="form-select w-auto">
                <option value="price_low_high" <?php if ($order_by == "price_per_night" && $order_dir == "ASC") echo 'selected'; ?>>Price: Low to High</option>
                <option value="price_high_low" <?php if ($order_by == "price_per_night" && $order_dir == "DESC") echo 'selected'; ?>>Price: High to Low</option>
                <option value="type" <?php if ($order_by == "type") echo 'selected'; ?>>Room Type</option>
                <option value="availability" <?php if ($order_by == "status") echo 'selected'; ?>>Availability</option>
            </select>
            <button type="submit" class="btn btn-primary ms-2">Apply</button>
        </form>
    </div>

    <div class="container my-5">
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($room = $result->fetch_assoc()): ?>
                    <div class="col-md-4 my-3">
                        <div class="card room-card border-0 shadow">
                            <img src="images/Rooms/<?php echo htmlspecialchars(basename($room['image_path'])); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($room['type']); ?>">
                            <div class="card-body">
                                <h5><?php echo htmlspecialchars($room['type']); ?></h5>
                                <p>
                                    Price: <span id="room_<?php echo $room['id']; ?>" data-price="<?php echo htmlspecialchars($room['price_per_night']); ?>">$<?php echo htmlspecialchars($room['price_per_night']); ?> USD</span> per night
                                </p>
                                <?php if ($room['status'] === 'available'): ?>
                                    <a href="book_room.php?room_no=<?php echo htmlspecialchars($room['room_no']); ?>" class="btn btn-primary">Book Now</a>
                                <?php else: ?>
                                    <button class="btn btn-danger" disabled>Not Available</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No rooms available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateRoomPrices() {
            const currency = document.getElementById('currency').value;
            const rooms = document.querySelectorAll('[id^="room_"]'); // Select all price elements

            rooms.forEach(room => {
                const basePrice = parseFloat(room.getAttribute('data-price')); // Get the original USD price

                const xhr = new XMLHttpRequest();
                xhr.open("GET", `convert_currency.php?currency=${currency}`, true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const rate = parseFloat(xhr.responseText);
                        const convertedPrice = (basePrice * rate).toFixed(2);
                        room.innerText = `${convertedPrice} ${currency}`;
                    }
                };
                xhr.send();
            });
        }
    </script>
</body>
</html>
