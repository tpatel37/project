<?php
session_start();

// File to store reviews
$review_file = 'reviews.json';

// Check if the user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php?message=Please log in to submit a review.");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review = trim($_POST['review'] ?? '');
    $user_name = $_SESSION['user_name']; // User's name from the session

    // Validate input
    if (empty($review)) {
        header("Location: index.php?message=Review cannot be empty.");
        exit();
    }

    // Load existing reviews
    $reviews = [];
    if (file_exists($review_file)) {
        $reviews = json_decode(file_get_contents($review_file), true) ?? [];
    }

    // Add the new review
    $new_review = [
        'id' => uniqid(),
        'user_name' => htmlspecialchars($user_name),
        'review' => htmlspecialchars($review),
        'status' => 'hidden', // Default status for new reviews
        'date' => date('Y-m-d H:i:s'),
    ];
    $reviews[] = $new_review;

    // Save reviews back to the file
    if (file_put_contents($review_file, json_encode($reviews, JSON_PRETTY_PRINT))) {
        header("Location: index.php?message=Review submitted for approval.");
    } else {
        header("Location: index.php?message=Error saving review.");
    }
    exit();
}
?>
