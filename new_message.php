<?php

session_start();

require_once "php/config.php";
$query = "
(SELECT * FROM `users` WHERE `id` IN 
 (SELECT `user2` FROM `friends` WHERE `user1` = ${_SESSION['id']} AND `accepted` = 1))
UNION
(SELECT * FROM `users` WHERE `id` IN 
 (SELECT `user1` FROM `friends` WHERE `user2` = ${_SESSION['id']} AND `accepted` = 1))
";
$friends = $mysqli->query($query);

$to = $to_err = $body_err = $body_text = $group_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = 0;
    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
    }
    if (isset($_POST["to"])) {
        $to = trim($_POST["to"]);
    }
    if (empty($to)) {
        $to_err = "You must select a friend.";
    }
    if (isset($_POST["body"])) {
        $body_text = trim($_POST["body"]);
    }
    if (empty($body_text)) {
        $body_err = "You must enter a message.";
    }
    if (empty($to_err) && empty($body_err)){
        $sql = "INSERT INTO `messages` (`from_id`, `to_id`, `message`) VALUES (${id}, ${to}, '${body_text}')";
        $result = $mysqli->multi_query($sql);
        $mysqli->close();
        header("location: index.php");
    }
} else {
    $mysqli->close();
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
        <p class="username"><?php echo $_SESSION["username"] ?></p>
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
    <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
        <div class="form-group <?php echo (!empty($to_err)) ? 'has-error' : ''; ?>">
            <label>To:</label>
            <select id="to" name="to">
                <option value="" disabled selected>Select a friend ...</option>
                <?php
                foreach ($friends as $friend) {
                    echo "<option value='${friend['id']}'>${friend['username']}</option>";
                }
                ?>
            </select>
            <span class="help-block"><?php echo $to_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($body_err)) ? 'has-error' : ''; ?>">
            <label>Message:</label>
            <textarea name="body" class="form-control"><?php echo $body_text; ?></textarea>
            <span class="help-block"><?php echo $body_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Send">
        </div>
    </form>
</div>
</body>
</html>