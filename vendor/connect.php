<?php
   $connect = mysqli_connect('localhost', 'root', password: '', database: 'soins');

   if (!$connect) {
      die('Connect error');
   }