<?php
// Start session and include config
session_start();
require_once 'config.php'; // Adjust the path as needed

$searchString = $_GET['search'] ?? '';

// Prevent SQL injection by using a prepared statement
$sql = "SELECT t.content, t.created_at, u.username FROM tweets t JOIN users u ON t.user_id = u.id WHERE t.content LIKE ? ORDER BY t.created_at DESC";
$stmt = $mysqli->prepare($sql);
$likeSearchString = "%" . $searchString . "%";
$stmt->bind_param("s", $likeSearchString);
$stmt->execute();
$result = $stmt->get_result();

// Fetch results
$tweets = [];
while ($row = $result->fetch_assoc()) {
    $tweets[] = $row;
}

// Close statement and connection
$stmt->close();
$mysqli->close();
?>

<!-- HTML to display search results -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    
</head>
<body>
    <h2>Search Results</h2>
    <div id="tweetsContainer">
        <?php foreach ($tweets as $tweet): ?>
            <div class="tweet">
                <p><strong><?php echo htmlspecialchars($tweet['username']); ?></strong>: <?php echo htmlspecialchars($tweet['content']); ?></p>
                <p><small><?php echo $tweet['created_at']; ?></small></p>
            </div>
        <?php endforeach; ?>

        <?php if (empty($tweets)): ?>
            <p>No tweets found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
