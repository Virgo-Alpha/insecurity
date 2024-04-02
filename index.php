<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "php/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add POST request handling logic here if needed
} else {
    // Use prepared statement to prevent SQL injection
    $query = "SELECT  a.*, "
                . "b.username 'from',"
                . "c.username 'to'    "
                . "FROM    `messages` a "
                . "     INNER JOIN users b "
                . "         ON  a.from_id = b.id "
                . "     INNER JOIN users c "
                . "         ON  a.to_id = c.id "
                . "WHERE (a.from_id = ? OR a.to_id = ?) AND a.thread_id IS NULL"; // Use placeholders for prepared statement
    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        // Bind parameters and execute the statement
        $stmt->bind_param("ii", $_SESSION['id'], $_SESSION['id']);
        $stmt->execute();
        $messages = $stmt->get_result();
        $stmt->close();
    } else {
        die("Error in preparing SQL statement: " . $mysqli->error);
    }
}

mysqli_close($mysqli);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User</title>
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
        <a class="active" href="new_message.php">New Message</a>
        <a class="active" href="users.php">Users</a>
        <a class="active" href="php/logout.php">Logout</a>
    </div>
    <div class="header-centre">
        <p>
            Message Threads
        </p>
    </div>
</div>
<div class="wrapper">
    <table class="table table-hover">
    <?php
    // Output messages
    foreach ($messages as $message) { ?>
        <tr>
            <td>
                <p><strong>From: </strong><?php echo htmlspecialchars($message['from']); ?>
                    <strong>To: </strong><?php echo htmlspecialchars($message['to']); ?>
                    <strong>At: </strong>(<?php echo htmlspecialchars($message['date']); ?>)
                </p>
                <p><?php echo htmlspecialchars($message['message']); ?></p> <!-- Sanitize output -->
            </td>
        </tr>
        <?php
        } ?>
    </table>
</div>
<div class="footer misty">
</div>
</body>
</html>
