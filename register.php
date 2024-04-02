<?php
// Include config file
require_once "php/config.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate username
    $username = sanitize_input($_POST["username"]); // Sanitize input
    if (empty($username)) {
        $username_err = "Please enter a username.";
    } else {
        // Prepare a select statement with prepared statements
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $username_err = "This username is already taken :(";
        }
        $stmt->close();
    }

    // Sanitize and validate password
    $password = sanitize_input($_POST["password"]); // Sanitize input
    if (empty($password)) {
        $password_err = "Please enter a password.";
    } elseif (strlen($password) < 6){
        $password_err = "Password must have at least 6 characters.";
    }

    // Validate confirm password
    $confirm_password = sanitize_input($_POST["confirm_password"]); // Sanitize input
    if (empty($confirm_password)) {
        $confirm_password_err = "Please confirm password.";
    } elseif ($password !== $confirm_password) {
        $confirm_password_err = "Password did not match.";
    }

    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
        // Prepare an insert statement with prepared statements
        $stmt = $mysqli->prepare("INSERT INTO users (username, password, admin) VALUES (?, ?, 0)");
        $hashed_password = md5($password);
        $stmt->bind_param("ss", $username, $hashed_password);
        if($stmt->execute()){
            // Redirect to login page
            header("location: login.php");
            exit();
        } else{
            echo "Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
    // Close connection
    $mysqli->close();
}

// Function to sanitize user input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<div class="wrapper-login misty">
    <h2>Sign Up</h2>
    <p>Please fill this form to create an account.</p>
    <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>">
            <span class="help-block"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Password</label>
            <input type="password" name="password" class="form-control" value="<?php echo htmlspecialchars($password); ?>">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" value="<?php echo htmlspecialchars($confirm_password); ?>">
            <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>
        <!-- CSRF token -->
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-default" value="Reset">
        </div>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
</div>
</body>
</html>
