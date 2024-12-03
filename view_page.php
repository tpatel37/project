<?php
require_once 'db_config.php';

// Get the page ID from the URL
$page_id = $_GET['id'] ?? 0;
$page_id = intval($page_id); // Ensure it's an integer

// Fetch the page from the database
$stmt = $conn->prepare("SELECT title, content FROM pages WHERE id = ?");
$stmt->bind_param("i", $page_id);
$stmt->execute();
$result = $stmt->get_result();

$page = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page['title'] ?? 'Page Not Found'); ?></title>
</head>
<body>
    <div class="container mt-5">
        <?php if ($page): ?>
            <h1><?php echo htmlspecialchars($page['title']); ?></h1>
            <div><?php echo $page['content']; ?></div>
        <?php else: ?>
            <p>Page not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
