<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'security');
define('DB_PASSWORD', 'security');
define('DB_NAME', 'security');

$mysqli = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($mysqli === false){
    die("ERROR: Could not connect to database.");
}
