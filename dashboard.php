<?php
session_start();

// Ensure the user is logged in; otherwise, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?show=login");
    exit();
}

// File to store reviews
$review_file = 'reviews.json';

// Check if the review form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $review = trim($_POST['review'] ?? '');
    $user_name = $_SESSION['user_name']; // Get the user's name from the session

    // Validate input
    if (empty($review)) {
        $error_message = "Review cannot be empty!";
    } else {
        // Load existing reviews
        $reviews = [];
        if (file_exists($review_file)) {
            $reviews = json_decode(file_get_contents($review_file), true) ?? [];
        }

        // Add the new review
        $new_review = [
            'user_name' => htmlspecialchars($user_name),
            'review' => htmlspecialchars($review),
            'date' => date('Y-m-d H:i:s'),
        ];
        $reviews[] = $new_review;

        // Save reviews back to the file
        file_put_contents($review_file, json_encode($reviews, JSON_PRETTY_PRINT));
        $success_message = "Review added successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Hotel Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container my-5">
        <!-- Welcome Message -->
        <h1 class="text-center fw-bold">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>

        <!-- Section: Fixed Services -->
        <h2 class="mt-5">Our Services</h2>
        <ul class="list-group">
            <li class="list-group-item">Room Cleaning - $20.00</li>
            <li class="list-group-item">Laundry - $15.00</li>
            <li class="list-group-item">Spa - $50.00</li>
        </ul>

        <!-- Link to Explore Rooms -->
        <div class="mt-4">
            <a href="explore_rooms.php" class="btn btn-primary">Explore Rooms</a>
        </div>

        <!-- Section: Ratings and Reviews -->
        <h2 class="mt-5">Ratings and Reviews</h2>

        <!-- Display Success or Error Messages -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success text-center">
                <?php echo $success_message; ?>
            </div>
        <?php elseif (!empty($error_message)): ?>
            <div class="alert alert-danger text-center">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Review Submission Form -->
<div class="container my-5">
    <h3 class="text-center fw-bold">Submit Your Review</h3>
    <?php if (isset($_SESSION['user_name'])): ?>
        <form method="POST" action="add_review.php" class="my-4">
            <div class="mb-3">
                <label for="review" class="form-label">Your Review</label>
                <textarea id="review" name="review" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Submit Review</button>
        </form>
    <?php else: ?>
        <p class="text-center">You need to <a href="index.php#loginbutton">log in</a> to submit a review.</p>
    <?php endif; ?>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
