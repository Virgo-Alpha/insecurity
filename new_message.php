<?php

session_start();

require_once "php/config.php";

$query = "
(SELECT * FROM `users` WHERE `id` IN 
 (SELECT `user2` FROM `friends` WHERE `user1` = ? AND `accepted` = 1))
UNION
(SELECT * FROM `users` WHERE `id` IN 
 (SELECT `user1` FROM `friends` WHERE `user2` = ? AND `accepted` = 1))
";

// Using prepared statement to prevent SQL injection
$stmt = $mysqli->prepare($query);
if ($stmt) {
    $stmt->bind_param("ii", $_SESSION['id'], $_SESSION['id']);
    $stmt->execute();
    $friends = $stmt->get_result();
    $stmt->close();
} else {
    die("Error in preparing SQL statement: " . $mysqli->error);
}

$to = $to_err = $body_err = $body_text = $group_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_SESSION['id']; // No need to check again

    // Sanitize and validate input
    $to = isset($_POST["to"]) ? trim($_POST["to"]) : '';
    $body_text = isset($_POST["body"]) ? trim($_POST["body"]) : '';

    if (empty($to)) {
        $to_err = "You must select a friend.";
    }
    if (empty($body_text)) {
        $body_err = "You must enter a message.";
    }

    if (empty($to_err) && empty($body_err)){
        // Using prepared statement to prevent SQL injection
        $stmt = $mysqli->prepare("INSERT INTO `messages` (`from_id`, `to_id`, `message`) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("iis", $id, $to, $body_text);
            if ($stmt->execute()) {
                $stmt->close();
                $mysqli->close();
                header("location: index.php");
                exit();
            } else {
                echo "Error in executing SQL statement: " . $stmt->error;
            }
        } else {
            echo "Error in preparing SQL statement: " . $mysqli->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>New Message</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<div class="header">
    <div class="header-left">
        <p class="username"><?php echo htmlspecialchars($_SESSION["username"]); ?></p> <!-- Sanitize output -->
    </div>
    <div class="header-right">
        <a class="active" href="index.php">Home</a>
        <a class="active" href="php/logout.php">Logout</a>
    </div>
    <div class="header-centre">
        <p>
            New Message
        </p>
    </div>
</div>
<div class="wrapper-login misty">
    <h2>New Message</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($to_err)) ? 'has-error' : ''; ?>">
            <label>To:</label>
            <select id="to" name="to">
                <option value="" disabled selected>Select a friend ...</option>
                <?php
                foreach ($friends as $friend) {
                    echo "<option value='" . htmlspecialchars($friend['id']) . "'>" . htmlspecialchars($friend['username']) . "</option>"; // Sanitize output
                }
                ?>
            </select>
            <span class="help-block"><?php echo $to_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($body_err)) ? 'has-error' : ''; ?>">
            <label>Message:</label>
            <textarea name="body" class="form-control"><?php echo htmlspecialchars($body_text); ?></textarea> <!-- Sanitize output -->
            <span class="help-block"><?php echo $body_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Send">
        </div>
    </form>
</div>
</body>
</html>