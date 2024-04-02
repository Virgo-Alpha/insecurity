<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "php/config.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

} else {
    $query = "SELECT  a.*, "
                . "b.username 'from',"
                . "c.username 'to'    "
                . "FROM    `messages` a "
                . "     INNER JOIN users b "
                . "         ON  a.from_id = b.id "
                . "     INNER JOIN users c "
                . "         ON  a.to_id = c.id "
                . "WHERE a.from_id = ${_SESSION['id']} OR a.to_id = ${_SESSION['id']} "
                . "AND a.thread_id IS NULL";
    $messages = $mysqli->query($query);
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
                <p class="username"><?php echo $_SESSION["username"] ?></p>
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
            foreach ($messages as $message) { ?>
                <tr>
                    <td>
                        <p><strong>From: </strong><?php echo $message['from']?>
                            <strong>To: </strong><?php echo $message['to']?>
                            <strong>At: </strong>(<?php echo $message['date'] ?>)
                        </p>
                        <p><?php echo $message['message']?></p>
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