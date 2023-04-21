<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/css/log.css">
    <link href='https://fonts.googleapis.com/css?family=Merriweather' rel='stylesheet'>
</head>
<body>
<div class="bg"></div>
<?php
      if($_COOKIE['user'] == ''):
?>
<div class="panel"><input type="radio" id="switch-open" name="switch" /><input type="radio" id="switch-close" name="switch" />
    <form action="vendor/login.php" method="post" enctype="multipart/form-data">
        <div class="login">
            <h1>LOGIN</h1>
            <div class="group">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <input type="text" name="email" id="email" placeholder="E-Mail" />
                <label for="email"></label>
            </div>
            <div class="group">
                <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                <input type="password" name="password" id="password" placeholder="Password" />
                <label for="password"></label>
            </div>
            <button type="submit" value="LOGIN" name="logsubmit" class="log-success">LOGIN</button>
            <a href="home.php">Homepage</a>
        </div>
    </form>
    <form action="vendor/register.php" method="post" enctype="multipart/form-data">
        <div class="register">
            <label class="button-open" for="switch-open"></label>
            <label class="button-close" for="switch-close"></label>
            <div class="inner">
                <h1>REGISTER</h1>
                <div class="group">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <input type="text" name="login" id="name" placeholder="Login" />
                    <label for="name"></label>
                </div>
                <div class="group">
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                    <input type="text" name="email" id="email" placeholder="E-Mail" />
                    <label for="email"></label>
                </div>
                <div class="group">
                    <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                    <input type="password" name="password" id="password" placeholder="Password" />
                    <label for="password"></label>
                </div>
                <button type="submit" value="REGISTER" name="regsubmit" class="reg-success">REGISTER</button>
                <a href="home.php">Homepage</a>
            </div>
        </div>
    </form>
</div>
<?php else: ?>

<?php endif;?>
</body>
<?php require_once("vendor/connect.php"); ?>