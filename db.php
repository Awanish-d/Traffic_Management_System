<?php

$servername = "sql205.infinityfree.com";   // apna actual DB host
$username   = "if0_41269365";              // DB username
$password   = "FusTpsXSrE4W4";     // yaha apna vPanel password
$database   = "if0_41269365_tms";          // apna DB name

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

?>