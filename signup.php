<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="css/signup.css">
</head>
<div class="blnkspc"></div>
<section class="signup-form">
    <center><h2>Sign Up</h2></center>
    <?php
    if(isset($_GET["error"])) {
        if($_GET["error"] == "emptyinput") {
            echo "<center><p class='error'>Fill in all fields!</p></center>";
        }
        else if ($_GET["error"] == "invalidusername") {
            echo "<center><p class='error'>Choose a proper username!</p></center>";
        }
        else if ($_GET["error"] == "invalidemail") {
            echo "<center><p class='error'>Choose a proper email!</p></center>";
        }
        else if ($_GET["error"] == "passwordsdontmatch") {
            echo "<center><p class='error'>Passwords doesn't match!</p></center>";
        }
        else if ($_GET["error"] == "stmtfailed"){
            echo "<center><p class='error'>Something went wrong, try again!</p></center>";
        }
        }
    ?>
        <div class="signup-form-form">
            <form action="includes/signup.inc.php" method="post"></center>
                <center><input type="text" name="name" placeholder="Full name"></center>
                <center><input type="text" name="email" placeholder="Email"></center>
                <center><input type="text" name="uid" placeholder="Username"></center>
                <center><input type="password" name="pwd" placeholder="Password"></center>
                <center><input type="password" name="pwdrepeat" placeholder="Confirm password"></center>
                <button type="submit" name="submit">Sign Up</button>
                <a href="/index.php">Homepage</a>
            </form>
        </div>
</section>