<?php
// Start the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$tweetContent = "";
$tweet_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate tweet content
    if(empty(trim($_POST["content"]))){
        $tweet_err = "Please enter tweet content.";
    } elseif(strlen(trim($_POST["content"])) > 280) {
        $tweet_err = "Tweet content must be under 280 characters.";
    } else {
        $tweetContent = trim($_POST["content"]);
    }
    
    // Check input errors before inserting in database
    if(empty($tweet_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO tweets (user_id, content) VALUES (?, ?)";
         
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("is", $param_user_id, $param_content);
            
            // Set parameters
            $param_user_id = $_SESSION["id"];
            $param_content = $tweetContent;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to main page
                header("Location: index.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Tweet</title>
    <!-- Bootstrap CSS Only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
