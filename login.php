<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login form</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<div class="blnkspc"></div>
<section class="login-form">
    <center><h2>Login</h2></center>
    <?php
    if(isset($_GET["error"])) {
        if($_GET["error"] == "emptyinput") {
            echo "<center><p class='error'>Fill in all fields!</p></center>";
        }
        else if ($_GET["error"] == "wronglofin") {
            echo "<center><p class='error'>Incorrect login!</p></center>";
        }
        }
    ?>
        <div class="login-form-form">
            <form action="includes/login.inc.php" method="post"></center>
                <center><input type="text" name="uid" placeholder="Username/Email"></center>
                <center><input type="password" name="pwd" placeholder="Password"></center>
                <center><button type="submit" name="submit">Login</button></center>
                <a href="/index.php">Homepage</a>
            </form>
        </div>
</section>