<?php
    $email = filter_var(trim($_POST ['email']), FILTER_SANITIZE_STRING);
    $password = filter_var(trim($_POST ['password']), FILTER_SANITIZE_STRING); 

    $password = md5($password."wd34t43tg");

    $mysql = new mysqli('localhost', 'root', '', 'soins');

    $result = $mysql->query("SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$password'");
    $user = $result->fetch_assoc();
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    }

    setcookie('user', $user['email'], time() + 3600, "/");

    $mysql->close();

    header('Location: ../authhome.php');
?>