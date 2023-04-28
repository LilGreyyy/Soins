<?php

$serverName = "localhost";
$dbUserName = "root";
$dbPassword = "";
$dbName = "soins";

//Connect to database
global $conn;
$conn = mysqli_connect($serverName, $dbUserName, $dbPassword, $dbName);

if (!$conn) {
    die("Something went wrong;");
}