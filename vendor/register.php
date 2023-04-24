<?php
    $login = filter_var(trim($_POST ['login']), FILTER_SANITIZE_STRING); 
    $email = filter_var(trim($_POST ['email']), FILTER_SANITIZE_STRING);
    $password = filter_var(trim($_POST ['password']), FILTER_SANITIZE_STRING); 

    $password = md5($password."wd34t43tg");

    $mysql = new mysqli('localhost', 'root', '', 'soins');
    $mysql->query("INSERT INTO `users` (`login`, `email`, `password`) VALUES('$login', '$email', '$password')");

    $mysql->close();

    header('Location: ../home.php');
?>