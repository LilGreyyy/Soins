<?php
require __DIR__ . "/mailer.php";

$conn = mysqli_connect('localhost', 'root', '', 'soins') or die('connection failed');

// Initialize message array
$message = array();

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);

    // Password validation
    if (strlen($password) < 8) {
        $message[] = 'Parolei jābūt vismaz 8 rakstzīmēm.';
    }

    if (!preg_match("#[0-9]+#", $password)) {
        $message[] = 'Parolei jāsatur vismaz viens cipars.';
    }

    if (!preg_match("#[A-Z]+#", $password)) {
        $message[] = 'Parolei jāsatur vismaz viens lielais burts.';
    }

    if (!preg_match("#[a-z]+#", $password)) {
        $message[] = 'Parolei jāsatur vismaz viena mazais burts.';
    }

    if (!preg_match("#[\W]+#", $password)) {
        $message[] = 'Parolei jāsatur vismaz viena speciāla rakstzīme.';
    }

    if ($password != $cpassword) {
        $message[] = 'Paroles nesakrīt.';
    }

    if (empty($message)) {
        // Hashing password if all checks pass
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if user already exists
        $select = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'");
        if (!$select) {
            die('Query error: ' . mysqli_error($conn));
        }

        if (mysqli_num_rows($select) > 0) {
            $message[] = 'Lietotājs jau eksistē';
        } else {
            // Insert user into database
            $insert = mysqli_query($conn, "INSERT INTO `users` (name, email, number, password) VALUES ('$name', '$email', '$number', '$hashed_password')") or die('Query error: ' . mysqli_error($conn));
        
            if ($insert) {
                header("Location: success.php"); // Перенаправляем на страницу success.php после успешной регистрации
                exit();
            } else {
                die('Query error: ' . mysqli_error($conn));
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="lv">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reģistrācija</title>
   <link rel="stylesheet" href="css/style.css">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
   <script>
      function togglePassword(input, icon, messageID) {
         var x = document.getElementById(input);
         var y = document.getElementById(icon);
         var message = document.getElementById(messageID);
         if (x.type === "password") {
            x.type = "text";
            y.innerHTML = '<i class="far fa-eye-slash"></i>';
            message.style.display = "block";
         } else {
            x.type = "password";
            y.innerHTML = '<i class="far fa-eye"></i>';
            message.style.display = "none";
         }
      }

      function validatePassword() {
         var password = document.getElementById('password').value;
         var requirements = document.getElementById('password-requirements');

         var lengthCheck = /[^\s]{8,}/.test(password);
         var numberCheck = /[0-9]/.test(password);
         var uppercaseCheck = /[A-Z]/.test(password);
         var lowercaseCheck = /[a-z]/.test(password);
         var specialCheck = /[\W_]/.test(password);

         var requirementsText = '';
         requirementsText += lengthCheck ? '<i class="fas fa-check"></i> ' : '<i class="fas fa-times"></i> ';
         requirementsText += numberCheck ? '<i class="fas fa-check"></i> ' : '<i class="fas fa-times"></i> ';
         requirementsText += uppercaseCheck ? '<i class="fas fa-check"></i> ' : '<i class="fas fa-times"></i> ';
         requirementsText += lowercaseCheck ? '<i class="fas fa-check"></i> ' : '<i class="fas fa-times"></i> ';
         requirementsText += specialCheck ? '<i class="fas fa-check"></i> ' : '<i class="fas fa-times"></i> ';

         requirements.innerHTML = requirementsText;

         if (lengthCheck && numberCheck && uppercaseCheck && lowercaseCheck && specialCheck) {
            requirements.style.color = 'green';
         } else {
            requirements.style.color = 'red';
         }
      }
   </script>
</head>
<body>
<div class="form-container">
   <form action="" method="post" enctype="multipart/form-data" class="signup-form">
      <h3>Reģistrācija</h3>
      <?php
      if (!empty($message)) {
         foreach ($message as $msg) {
            echo '<div class="message">' . $msg . '</div>';
         }
      }
      ?>
      <input type="text" name="name" placeholder="Vārds Uzvārds" class="box" required>
      <input type="email" name="email" placeholder="E-pasts" class="box" required>
      <input type="text" name="number" placeholder="Numurs" class="box" required>
      <div style="position: relative;">
         <input type="password" name="password" id="password" placeholder="Parole" class="box" required oninput="validatePassword()">
         <div class="password-toggle" onclick="togglePassword('password', 'password-toggle', 'password-requirements')">
            <i class="far fa-eye"></i>
         </div>
         <div id="password-requirements" class="password-requirements">
            <i class="fas fa-times"></i> Parolei jābūt vismaz 8 rakstzīmēm, jāsatur vismaz viens cipars, viens lielais burts, viens mazais burts un viena speciālā rakstzīme.
         </div>
      </div>
      <div style="position: relative;">
         <input type="password" name="cpassword" id="cpassword" placeholder="Apstipriniet paroli" class="box" required>
         <div class="password-toggle" onclick="togglePassword('cpassword', 'cpassword-toggle')">
            <i class="far fa-eye"></i>
         </div>
      </div>
      <input type="submit" name="submit" value="Reģistrēties" class="btn">
      <p>Jau ir konts? <a href="login.php">Pieraksties tagad</a></p>
      <p><a href="index.php">Mājas lapa</a></p>
   </form>
</div>
</body>
</html>
