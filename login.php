<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Initialize the session
session_start();

//Check if the user is already logged in, if yes then redirect to the welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
} 


// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if username exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $username, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            // Redirect user to welcome page
                            header("location: index.php");
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            
            $stmt->close();
        }
    }

    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
    <style>
        

body {
  font-family: 'Arial', sans-serif;
  background-color: #091e42; 
  color: #ffffff;
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.wrapper {
  width: 350px;
  padding: 20px;
  background-color: #0a2a46;
  border-radius: 5px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #17a2b8; 
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
}

.form-control {
  width: 100%;
  padding: 10px;
  border-radius: 5px;
  border: none;
  font-size: 16px;
}

.btn-primary {
  background-color: #007bff;
  border-color: #007bff;
  color: white;
  padding: 10px;
  width: 100%;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s ease;
}

.btn-primary:hover {
  background-color: #0056b3;
}

.help-block {
  color: #ff3860;
}

a {
  color: #17a2b8;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

p {
  text-align: center;
}

.btn-welcome {
    display: inline-block;
    padding: 10px 20px;
    margin-top: 20px; 
    font-size: 16px;
    color: #fff;
    background-color: #007bff; 
    border: none;
    border-radius: 5px;
    text-decoration: none; /* Removes underline from links */
    transition: background-color 0.3s ease;
}

/* Back button container */
.back-button-container {
    display: flex;
    justify-content: center; /* Center button horizontally */
    align-items: center; /* Center button vertically */
    margin-top: 20px; /* Space from the top */
}

.btn-welcome {
    padding: 10px 20px;
    font-size: 16px;
    color: white;
    background-color: #17a2b8; 
    border-radius: 5px;
    text-decoration: none; 
    transition: background-color 0.3s, transform 0.3s; 
}

.btn-welcome:hover, .btn-welcome:focus {
    background-color: #138496; 
    text-decoration: none; 
    transform: translateY(-2px); 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
}


    </style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NewTwitter </title>
    
</head>
<body>


    <div class="wrapper">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
            <div class="back-button-container">
     <div class="back-button-container">
     <form action= "welcome.php" method="get">
     <a href="welcome.php" class="btn btn-primary">Back to Welcome Page</a>
     </form>
    </div>  

</div>




        </form>
       
</div>
  
    
</body>
</html>
