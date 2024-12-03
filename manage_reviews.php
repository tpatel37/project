<?php
session_start();

// File to store reviews
$review_file = 'reviews.json';

// Check if the user is an admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php?message=Access denied.");
    exit();
}

// Load reviews
$reviews = [];
if (file_exists($review_file)) {
    $reviews = json_decode(file_get_contents($review_file), true) ?? [];
}

// Handle moderation actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = $_POST['review_id'];
    $action = $_POST['action'];

    foreach ($reviews as &$review) {
        if ($review['id'] === $review_id) {
            if ($action === 'approve') {
                $review['status'] = 'approved';
            } elseif ($action === 'hide') {
                $review['status'] = 'hidden';
            } elseif ($action === 'disemvowel') {
                $review['review'] = preg_replace('/[aeiou]/i', '', $review['review']);
            } elseif ($action === 'delete') {
                $reviews = array_filter($reviews, fn($r) => $r['id'] !== $review_id);
            }
            break;
        }
    }

    // Save the updated reviews
    file_put_contents($review_file, json_encode($reviews, JSON_PRETTY_PRINT));
    header("Location: manage_reviews.php?message=Action performed successfully.");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Reviews</title>
</head>
<body>
    <h1>Review Moderation</h1>
    <?php if (isset($_GET['message'])): ?>
        <p><?php echo htmlspecialchars($_GET['message']); ?></p>
    <?php endif; ?>
    <table border="1">
        <thead>
            <tr>
                <th>User</th>
                <th>Review</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reviews as $review): ?>
                <tr>
                    <td><?php echo htmlspecialchars($review['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($review['review']); ?></td>
                    <td><?php echo htmlspecialchars($review['status']); ?></td>
                    <td><?php echo htmlspecialchars($review['date']); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                            <button name="action" value="approve">Approve</button>
                            <button name="action" value="hide">Hide</button>
                            <button name="action" value="disemvowel">Disemvowel</button>
                            <button name="action" value="delete" onclick="return confirm('Are you sure?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
