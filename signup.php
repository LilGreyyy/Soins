<?php
include 'includes/dbh.inc.php';

$message = array(); // Initialize the message array

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);

    // Password validation
    if (strlen($password) < 8) {
        $message[] = 'Password should be at least 8 characters long.';
    }
    
    if (!preg_match("#[0-9]+#", $password)) {
        $message[] = 'Password should contain at least one number.';
    }

    if (!preg_match("#[A-Z]+#", $password)) {
        $message[] = 'Password should contain at least one uppercase letter.';
    }

    if (!preg_match("#[a-z]+#", $password)) {
        $message[] = 'Password should contain at least one lowercase letter.';
    }

    if (!preg_match("#[\W]+#", $password)) {
        $message[] = 'Password should contain at least one special character.';
    }

    if ($password != $cpassword) {
        $message[] = 'Passwords do not match.';
    }

    if (empty($message)) {
        // Hash the password if all validations pass
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $select = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');

        if (mysqli_num_rows($select) > 0) {
            $message[] = 'User already exists';
        } else {
            $insert = mysqli_query($conn, "INSERT INTO `users` (name, email, password) VALUES ('$name', '$email', '$hashed_password')") or die('query failed');

            if ($insert) {
                $message[] = 'Registered successfully!';
                header('location: index.php');
            } else {
                $message[] = 'Registration failed!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sign Up</title>
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Sign Up</h3>
      <?php
      if(isset($message)){
         foreach($message as $message){
            echo '<div class="message">'.$message.'</div>';
         }
      }
      ?>
      <input type="text" name="name" placeholder="Username" class="box" required>
      <input type="email" name="email" placeholder="Email" class="box" required>
      <input type="password" name="password" placeholder="Password" class="box" required>
      <input type="password" name="cpassword" placeholder="Confirm password" class="box" required>
      <input type="submit" name="submit" value="Sign Up" class="btn">
      <p>Already have an account? <a href="login.php">Login now</a></p>
      <p><a href="index.php">Homepage</a></p>
   </form>

</div>

</body>
</html>