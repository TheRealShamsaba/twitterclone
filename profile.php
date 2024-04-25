<?php
ini_set('log_errors', 1);
ini_set('error_log', 'path_to_error_log/error_log.txt');
// Start the session
session_start();
require_once "config.php";

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include your database connection file
require_once "config.php";

// Retrieve user information from the database
// Dummy data for demonstration purposes
$userInfo = [
    'username' => $_SESSION["username"] ?? 'Unknown', // Replace with actual data from the database
    'bio' => 'This is a short bio about the user.', // Replace with actual data from the database
    'join_date' => 'January 1, 2024' // Replace with actual data from the database
];



// Fetch the user's tweets from the database
// Assuming you have a variable $userId that stores the logged-in user's ID
$sql = "SELECT id, content, created_at FROM tweets WHERE user_id = :userId ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':userId', $_SESSION['id'], PDO::PARAM_INT); // Assuming the user's ID is stored in the session
$stmt->execute();
$tweets = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($userInfo['username']); ?>'s Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">


    <style>
        
        body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
}

.profile-header {
    background-color: #007bff;
    color: #ffffff;
    padding: 2rem;
    margin-bottom: 2rem;
    border-radius: 0.5rem;
}

.tweet {
    background-color: #ffffff;
    border: 1px solid #ddd;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 0.5rem;
    position: relative;
}

/* Style for buttons */
.btn-edit,
.btn-delete {
    border-radius: 0.25rem; 
    padding: 0.375rem 0.75rem; 
    margin-right: 0.5rem; 
    border: none; 
    font-size: 0.875rem; 
    line-height: 1.5; 
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; 
}

.btn-edit {
    background-color:fuchsia;
    color: white;
}

.btn-delete {
    background-color:#007bff; 
    color: white;
}

/* Hover effects */
.btn-edit:hover {
    background-color:darkblue; 
}

.btn-delete:hover {
    background-color:darkblue; 
}


.buttons-container {
    white-space: nowrap;
}
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Edit functionality
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const tweetId = this.getAttribute('data-tweet-id');
                    let currentContent = document.querySelector('#tweet-' + tweetId + ' .tweet-content').innerText;
                    let newContent = prompt('Edit your tweet', currentContent);

                    if (newContent && newContent !== currentContent) {
                        fetch('edit_tweet.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: 'id=' + tweetId + '&content=' + encodeURIComponent(newContent)
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.querySelector('#tweet-' + tweetId + ' .tweet-content').innerText = newContent;
                                } else {
                                    alert('Error updating tweet');
                                }
                            });
                    }
                });
            });

            // Delete functionality
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const tweetId = this.getAttribute('data-tweet-id');
                    if (confirm('Are you sure you want to delete this tweet?')) {
                        fetch('delete_tweet.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: 'id=' + tweetId
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('tweet-' + tweetId).remove();
                                } else {
                                    alert('Error deleting tweet');
                                }
                            });
                    }
                });
            });
        });
    </script>


</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">NewTwitter</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="profile-header text-center">
        <h1>Welcome, <?php echo htmlspecialchars($userInfo['username']); ?>!</h1>
        <p>Joined on <?php echo $userInfo['join_date']; ?></p>
    </header>

    <main class="container">
        <section class="profile-info">
            <h2>About Me</h2>
            <p><?php echo nl2br(htmlspecialchars($userInfo['bio'])); ?></p>
        </section>
        

        <!-- Recent Tweets heading -->
        <section class="tweets-list">
            <h3>Recent Tweets</h3>
            <!-- Tweets will be loaded here -->
        </section>
        <!-- Tweets are dynamically loaded here -->
        <?php foreach ($tweets as $tweet) : ?>
            
                    <div class="card mb-2 tweet card-body card-title card-text" id="tweet-<?= $tweet['id']; ?>">
                    <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($tweet['username']); ?></h5>
                    <p class="tweet-content"><?= htmlspecialchars($tweet['content']); ?></p>
                    <p class="card-text">
                        <small class="text-muted ">
                            <?php echo $tweet['created_at']; ?>
                    </small>
                    </p>
                    </div>
                    </div>
                    <div class="buttons-container">
                        <button class="btn btn-primary edit-btn" data-tweet-id="<?= $tweet['id']; ?>">Edit</button>
                        <button class="btn btn-danger delete-btn" data-tweet-id="<?= $tweet['id']; ?>">Delete</button>
                    </div>
                    </div>
            
            </div>
            </div>
            </div>
        <?php endforeach; ?>
    </main>

    <footer class="bg-light text-center text-lg-start mt-4">
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2024 NewTwitter
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>