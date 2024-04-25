<?php
// Initialize the session.
session_start();

// Unset all of the session variables.
$_SESSION = array();

// Destroy the session.
session_destroy();

// Include headers to control the browser cache
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Redirect to login page
header("Location: welcome.php");
exit;
?>
