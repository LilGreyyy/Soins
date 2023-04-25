<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "soins";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if (!$conn) {
    die("Something went wrong;");
}
?>