<?php
session_start();
require_once 'db_config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: index.php"); // Redirect to the login page
    exit();
}

// Fetch all rooms
$rooms = $conn->query("SELECT * FROM rooms");
if (!$rooms) {
    die("Error fetching rooms: " . $conn->error);
}

// Fetch all bookings
$bookings = $conn->query("
    SELECT b.id, r.room_no, u.username, b.check_in_date, b.check_out_date, b.status
    FROM bookings b
    JOIN rooms r ON b.room_id = r.id
    JOIN users u ON b.user_id = u.id
");
if (!$bookings) {
    die("Error fetching bookings: " . $conn->error);
}

// Fetch all users
$users = $conn->query("SELECT * FROM users");
if (!$users) {
    die("Error fetching users: " . $conn->error);
}

// Load reviews from the JSON file
$review_file = 'reviews.json';
$reviews = [];
if (file_exists($review_file)) {
    $reviews = json_decode(file_get_contents($review_file), true) ?? [];
}

// Handle review moderation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_action'])) {
    $review_id = $_POST['review_id'];
    $action = $_POST['review_action'];

    foreach ($reviews as &$review) {
        if ($review['id'] === $review_id) {
            if ($action === 'approve') {
                $review['status'] = 'visible';
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

    // Save updated reviews back to the JSON file
    file_put_contents($review_file, json_encode($reviews, JSON_PRETTY_PRINT));
    header("Location: admin_dashboard.php?message=Action performed successfully.");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></h1>
        <a href="logout.php" class="btn btn-danger">Logout</a>

        <!-- Manage Rooms Section -->
        <h2 class="mt-4">Manage Rooms</h2>
        <a href="add_room.php" class="btn btn-primary mb-3">Add New Room</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Room No</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($room = $rooms->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($room['room_no']); ?></td>
                        <td><?php echo htmlspecialchars($room['type']); ?></td>
                        <td><?php echo htmlspecialchars($room['price_per_night']); ?></td>
                        <td><?php echo htmlspecialchars($room['status']); ?></td>
                        <td>
                            <a href="edit_room.php?id=<?php echo $room['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete.php?id=<?php echo $room['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Manage Reviews Section -->
        <h2 class="mt-4">Manage Reviews</h2>
        <table class="table table-bordered">
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
                                <button name="review_action" value="approve" class="btn btn-success btn-sm">Approve</button>
                                <button name="review_action" value="hide" class="btn btn-warning btn-sm">Hide</button>
                                <button name="review_action" value="disemvowel" class="btn btn-secondary btn-sm">Disemvowel</button>
                                <button name="review_action" value="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Manage Users Section -->
        <h2 class="mt-4">Manage Users</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Phone</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
