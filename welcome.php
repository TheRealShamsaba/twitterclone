
<?php

// At the start of every page that requires authentication
session_start();

// if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
//     // The user is not logged in, redirect them to the login page
//     header("location: login.php");
//     exit;
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
            
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #15202B; 
    color: #ffffff;
    line-height: 1.6;
    padding-bottom: 100px; 
}

header {
    background-color: #1DA1F2;
    padding: 1rem 0;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

header h1 {
    margin: 0;
    font-size: 2.5rem;
    color: #ffffff;
}

nav ul {
    list-style: none;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 1rem 0;
}

nav ul li {
    margin: 0 10px;
}

nav ul li a {
    color: #ffffff;
    text-decoration: none;
    font-size: 1.2rem;
    transition: color 0.3s ease;
}

nav ul li a:hover,
nav ul li a:focus {
    color: #AAB8C2;
}

main {
    width: 90%;
    max-width: 1200px;
    margin: 2rem auto;
    padding: 2rem;
    background: #192734;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
}

main p {
    font-size: 1.1rem;
    margin-bottom: 1rem;
}

footer {
    background-color: #1DA1F2;
    color: #ffffff;
    text-align: center;
    padding: 1rem 0;
    position: fixed;
    bottom: 0;
    width: 100%;
}


h1, h2, h3, h4, h5, h6 {
    margin-bottom: 0.5rem;
    font-weight: 500;
}

p {
    margin-bottom: 1rem;
}


a {
    color: #1DA1F2;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}


button,
input[type="submit"],
.input-group-text {
    font: inherit;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 5px;
    color: #ffffff;
    background: #1DA1F2;
    cursor: pointer;
    transition: background 0.3s ease;
}

button:hover,
input[type="submit"]:hover,
.input-group-text:hover {
    background: #1991db;
}


.container {
    width: 100%;
    margin: auto;
    max-width: 1200px;
    padding: 0 50px;
}

.text-center {
    text-align: center;
}

.mt-2 {
    margin-top: 2rem;
}

.mb-2 {
    margin-bottom: 2rem;
}

.p-2 {
    padding: 2rem;
}

/* Custom Styles */
.welcome-message {
    font-size: 1.5rem;
    text-align: center;
    margin-bottom: 2rem;
}

        </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to New Twitter</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <header>
        <h1>Welcome to NewTwitter</h1>
        <nav>
            <ul>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <p>Welcome to New Twitter, a place to share your thoughts with the world. Connect with friends, family, and other fascinating people. Get in-the-moment updates on the things that interest you. And watch events unfold, in real time, from every angle.</p>
        <p>Already have an account? <a href="login.php">Login here</a></p>
        <p>New to New Twitter? <a href="register.php">Create an account</a></p>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arian Twitter</p>
    </footer>

</body>
</html>
