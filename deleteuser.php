<?php
include 'includes/dbh.inc.php';
session_start();
$user_id = $_SESSION['user_id'];

if(isset($_GET['id'])){
   $id = $_GET['id'];
   
   // Удаление пользователя из базы данных
   $delete = mysqli_query($conn, "DELETE FROM `users` WHERE id = '$id'") or die('query failed');
   
   // Удаление сессии и перенаправление на страницу логина
   unset($_SESSION['user_id']);
   session_destroy();
   header('location: login.php');
}
else{
   // Если параметр id не передан, перенаправить на домашнюю страницу
   header('location: authindex.php');
}
?>
