<?php
    $login = filter_var(trim($_POST ['login']), FILTER_SANITIZE_STRING); 
    $email = filter_var(trim($_POST ['email']), FILTER_SANITIZE_STRING);
    $password = filter_var(trim($_POST ['password']), FILTER_SANITIZE_STRING); 

    if(mb_strlen($login) < 5 || mb_strlen($login) > 90) {
        echo "Invalid login length";
        exit();
    } else if(mb_strlen($email) < 7 || mb_strlen($email) > 75) {
        echo "Invalid email length";
        exit();
    } else if(mb_strlen($password) < 6 || mb_strlen($password) > 75) {
        echo "Invalid password length";
        exit();
    }

    $password = md5($password."wd34t43tg");

    $mysql = new mysqli('localhost', 'root', '', 'soins');
    $mysql->query("INSERT INTO `users` (`login`, `email`, `password`) VALUES('$login', '$email', '$password')");

    $mysql->close();

    header('Location: ../home.php');
?>