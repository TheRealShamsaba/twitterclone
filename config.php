<?php
// Database Configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "twitter";

// Create mysqli object
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
try {
    $pdo = new PDO("mysql: servername=$servername ;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>