<?php
require_once 'db_config.php'; // Database connection

// Number of results per page (N)
$results_per_page = 5;

// Get the current page from the URL, default is 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the offset for the SQL query
$offset = ($page - 1) * $results_per_page;

// Get the search query from the URL
$query = $_GET['query'] ?? '';
$query = htmlspecialchars($query, ENT_QUOTES, 'UTF-8'); // Sanitize input

$pages = [];
$total_results = 0;

if ($query) {
    // Prepare the SQL query to count total results
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM pages WHERE title LIKE ? OR content LIKE ?");
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_results = $result->fetch_assoc()['total'];
    $stmt->close();

    // Prepare the SQL query to fetch paginated results
    $stmt = $conn->prepare("SELECT id, title, file_path FROM pages WHERE title LIKE ? OR content LIKE ? LIMIT ? OFFSET ?");
    $stmt->bind_param("ssii", $searchTerm, $searchTerm, $results_per_page, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $pages[] = $row;
    }
    $stmt->close();
}

// Calculate the total number of pages
$total_pages = ceil($total_results / $results_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Search Results for "<?php echo htmlspecialchars($query); ?>"</h1>

        <?php if (!empty($pages)): ?>
            <ul class="list-group mb-4">
                <?php foreach ($pages as $page): ?>
                    <li class="list-group-item">
                        <a href="<?php echo htmlspecialchars($page['file_path']); ?>">
                            <?php echo htmlspecialchars($page['title']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Pagination Links -->
            <nav>
                <ul class="pagination">
                    <!-- Previous Page Link -->
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?query=<?php echo urlencode($query); ?>&page=<?php echo $page - 1; ?>">Previous</a>
                        </li>
                    <?php endif; ?>

                    <!-- Page Number Links -->
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?query=<?php echo urlencode($query); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next Page Link -->
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?query=<?php echo urlencode($query); ?>&page=<?php echo $page + 1; ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php else: ?>
            <p>No pages found matching your query.</p>
        <?php endif; ?>
    </div>
</body>
</html>
