<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Initialize the session
session_start();


// Include config file
require_once "config.php";

// Fetch tweets from the database
$sql = "SELECT t.content, t.created_at, u.username FROM tweets t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC";
$tweets = [];

// Execute the query
if ($result = $mysqli->query($sql)) {
    // Fetch all tweets
    while ($row = $result->fetch_assoc()) {
        $tweets[] = $row;
    }
    // Free the result
    $result->free();
} else {
    // Handle error - inform the user, log, etc.
    echo "Error fetching tweets: " . $conn->error;
}
// Define the number of tweets per page
$recordsPerPage = 5;

// Determine the current page; default to 1 if not set
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the starting point for the results
$startPoint = ($currentPage - 1) * $recordsPerPage;

// Fetch tweets from the database with pagination
$sql = "SELECT t.content, t.created_at, u.username FROM tweets t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC LIMIT ?, ?";
if ($stmt = $mysqli->prepare($sql)) {
    // Bind the parameters for the LIMIT clause
    $stmt->bind_param("ii", $startPoint, $recordsPerPage);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $tweets = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
    $stmt->close();
}

// Calculate the total number of pages
$sql = "SELECT COUNT(*) FROM tweets";
$totalTweets = $mysqli->query($sql)->fetch_row()[0];
$totalPages = ceil($totalTweets / $recordsPerPage);


$mysqli->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arian Twitter Clone</title>
    <!-- Bootstrap CSS Only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    

    <style>
    
        body,html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color:whitesmoke;
        }
        
        .navbar-custom {
            background-color: #004d99;
        }
        
        .btn-custom {
            background-color:wheat; 
            color:#007bff;
        }

        .btn-custom:hover {
            background-color: #007bff;
        }

        .container-custom {
            padding-top: 2rem;
        }

        .tweet-box textarea {
            
            margin-bottom: 1rem;
        }

        .tweets-list {
            margin-top: 2rem;
        }

        .search-container {
    display: flex;
    justify-content: center;
    padding: 20px;
}

.search-container input[type="text"] {
    width: 60%;
    padding: 10px;
    margin-right: 10px;
    border: 2px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

.search-container button {
    padding: 10px 20px;
    background-color: #007bff; 
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.search-container button:hover {
    background-color: #0056b3;
}


.fa-search {
    margin-right: 5px;
}

.highlight {
        background-color: yellow;
    }
    .pagination {
    display: inline-block;
    padding-left: 0;
    margin: 20px 0;
    border-radius: 4px;
}
* Pagination container */
    .pagination {
        justify-content: center; /* Center the pagination */
        padding: 20px 0; /* Add some padding on the top and bottom */
    }

    /* Pagination items */
    .page-item {
        margin: 0 5px; 
    }

    /* Active page item */
    .page-item.active .page-link {
        background-color: #004d99; 
        border-color: #004d99; 
    }

    /* Page links */
    .page-link {
        color: #004d99; 
        transition: color 0.3s, background-color 0.3s; 
    }

    /* Hover effect for page links */
    .page-link:hover {
        color: #fff; 
        background-color: #0059b3; 
        border-color: #0059b3; 
    }

    </style>


</head>
<body>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    


<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
  
        <a class="navbar-brand" href="#"> NewTwitter</ a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="profile.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
        
    </div>
</section>
    
</nav>
  
<main class="container container-custom">
    
<div class="container-fluid">
        <div class="search-container">
    <form action="search_tweets.php" method="get">
        <input type="text" placeholder="Search tweets..." name="search">

        <button type="submit"><i class="fa fa-search"></i> Search</button>
    </form>
    </div>

    <section class="tweet-box">
        <form action="post_tweet.php" method="post">
            <textarea class="form-control" name="content" rows="3" placeholder="What's happening?"></textarea>
            <button type="submit" class="btn btn-custom">Tweet</button>
        </form>
    
    <div class="container mt-4">
        <h2>Recent Tweets</h2>
        <?php foreach ($tweets as $tweet): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($tweet['username']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($tweet['content']); ?></p>
                    
                
                    <p class="card-text"><small class="text-muted"><?php echo $tweet['created_at']; ?></small></p>
                </div>
            </div>
        <?php endforeach; ?>
        <nav aria-label="Page navigation">
  <ul class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <li class="page-item <?php if ($i == $currentPage) echo 'active'; ?>">
      <a class="page-link" href="index.php?page=<?= $i; ?>"><?= $i; ?></a>
    </li>
    <?php endfor; ?>
  </ul>
</nav>
    </div>


            

    
    </section>
</main>
<!-- Footer -->
<footer class="bg-dark text-center text-lg-start text-white">
    <!-- Grid container -->
    <div class="container p-4">
        <!--Grid row-->
        <div class="row">
            <!--Grid column-->
            <div class="text-center" >
                <h5 class="text-uppercase">New Twitter</h5>

                <p>
                   words are more valuable than what we think
                </p>
            </div>
            <!--Grid column-->

            <!--Grid column-->
            <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
               

                </ul>
            </div>
            <!--Grid column-->
        </div>
        <!--Grid row-->
    </div>
    <!-- Grid container -->

    <!-- Copy-right -->
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        © 2024 Created by: Arian 
    
    </div>
    
</footer>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
