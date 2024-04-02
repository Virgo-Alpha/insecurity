<?php
session_start();

//if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
//    header("location: login.php");
//    exit;
//}
require_once "php/config.php";

// Use prepared statement to prevent SQL injection
$id = 0;
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
}
$query = "SELECT * FROM `users` WHERE `id` != ?";
$stmt = $mysqli->prepare($query);
if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $users = $stmt->get_result();
    $stmt->close();
} else {
    die("Error in preparing SQL statement: " . $mysqli->error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && isset($_POST['user_id'])) {
        $action = $_POST['action'];
        $user = $_POST['user_id'];
        $query = "";
        // Prevent SQL injection by using prepared statements
        if ($action == 'cancel') {
            $query = "DELETE FROM `friends` WHERE `user1` = ? AND `user2` = ?";
        } elseif ($action == 'accept') {
            $query = "UPDATE `friends` SET `accepted` = 1, `rejected` = 0, `invited` = 0 WHERE `user1` = ? AND `user2` = ?";
        } elseif ($action == 'invite') {
            $query = "INSERT INTO `friends` (`user1`, `user2`) VALUES (?, ?)";
        }
        if ($query) {
            $stmt = $mysqli->prepare($query);
            if ($stmt) {
                $stmt->bind_param("ii", $id, $user);
                $stmt->execute();
                $stmt->close();
            } else {
                die("Error in preparing SQL statement: " . $mysqli->error);
            }
        }
    }
    header("location: users.php");
    exit;
} else {
    // Retrieve user friendships with prepared statements
    $query = "SELECT * FROM `friends` where `user1` = ?";
    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $results = $stmt->get_result();
        $stmt->close();
    } else {
        die("Error in preparing SQL statement: " . $mysqli->error);
    }

    $friends_accepted = [];
    $friends_rejected = [];
    $friends_invited = [];
    foreach ($results as $friendship) {
        $friends_accepted[$friendship['user2']] = $friendship['accepted'];
        $friends_invited[$friendship['user2']] = $friendship['invited'];
        $friends_rejected[$friendship['user2']] = $friendship['rejected'];
    }

    // Retrieve invitations with prepared statements
    $invitations = [];
    $query = "SELECT * FROM `friends` where `user2` = ? AND `invited` = true";
    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $results = $stmt->get_result();
        $stmt->close();
    } else {
        die("Error in preparing SQL statement: " . $mysqli->error);
    }
    foreach ($results as $invitation) {
        $invitations[] = $invitation['user1'];
    }
}
mysqli_close($mysqli);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Users</title>
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
            Users
        </p>
    </div>
</div>
<div class="posts">
    <div class="wrapper misty">
        <table class="table">
            <thead>
            <td>Name</td><td>Friendship</td>
            </thead>
            <?php
            foreach ($users as $user) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['username']); ?></td> <!-- Sanitize output -->
                    <td>
                        <?php
                            if (isset($friends_rejected[$user['id']]) && $friends_rejected[$user['id']]) {
                                echo "Rejected :(";
                            } elseif (isset($friends_accepted[$user['id']]) && $friends_accepted[$user['id']]) {
                                echo "Friends :)";
                            } elseif (isset($friends_invited[$user['id']]) && $friends_invited[$user['id']]) {
                        ?>
                        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <input type="hidden" name="action" value="cancel">
                            <input type="submit" class="btn btn-primary" value="Cancel">
                        </form>
                        <?php
                            } elseif (in_array($user['id'], $invitations)) {
                        ?>
                        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <input type="hidden" name="action" value="accept">
                            <input type="submit" class="btn btn-primary" value="Accept">
                        </form>
                        <?php
                            } else {
                        ?>
                        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <input type="hidden" name="action" value="invite">
                            <input type="submit" class="btn btn-primary" value="Invite">
                        </form>
                        <?php
                            }
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
<div class="footer misty">
</div>
</body>
</html>