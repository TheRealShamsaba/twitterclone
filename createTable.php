<?php
require_once 'config.php'; // Include your database configuration file

// SQL to create a 'users' table
$sqlUsers = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

// SQL to create a 'tweets' table
$sqlTweets = "CREATE TABLE IF NOT EXISTS tweets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content VARCHAR(280) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

// Execute the query for 'users' table
if ($conn->query($sqlUsers) === TRUE) {
    echo "Table 'users' created successfully. ";
} else {
    echo "Error creating 'users' table: " . $conn->error . ". ";
}

// Execute the query for 'tweets' table
if ($conn->query($sqlTweets) === TRUE) {
    echo "Table 'tweets' created successfully. ";
} else {
    echo "Error creating 'tweets' table: " . $conn->error . ". ";
}

// Close connection
$conn->close();
?>
