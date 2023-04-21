<?php
    $email = filter_var(trim($_POST ['email']), FILTER_SANITIZE_STRING);
    $password = filter_var(trim($_POST ['password']), FILTER_SANITIZE_STRING); 

    $password = md5($password."wd34t43tg");

    $mysql = new mysqli('localhost', 'root', '', 'soins');

    $result = $mysql->query("SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$password'");
    $user = $result->fetch_assoc();
    if(count($user) == 0) {
        echo "User not found";
        exit();
    }

    setcookie('user', $user['email'], time() + 3600 * 24, "/");

    $mysql->close();

    header('Location: ../home.php');
?>