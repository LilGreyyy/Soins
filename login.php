<?php
include 'includes/dbh.inc.php';
session_start();
// Check if there are stored error messages in the session
if (isset($_SESSION['error_messages'])) {
    $message = $_SESSION['error_messages'];
    unset($_SESSION['error_messages']); // Clear the session variable
}

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = trim($_POST['password']);

    $select = mysqli_prepare($conn, "SELECT id, user_type, password FROM `users` WHERE LOWER(email) = LOWER(?) LIMIT 1");

    if (!$select) {
         die('Preparation failed: ' . mysqli_error($conn) . ' SQL: ' . $sql);
    }

    mysqli_stmt_bind_param($select, "s", $email);
    mysqli_stmt_execute($select);

    $result = mysqli_stmt_get_result($select);

    if (!$result) {
        die('Execution failed: ' . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $id = $row['id'];
        $usertype = $row['user_type'];
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            if ($usertype == 0) {
                header('location: index.php');
            } else if ($usertype == 1) {
                header('location: admin/admin.php');
            }
        } else {
         $message[] = 'Incorrect password!';
         error_log('Incorrect email or password for email: ' . $email . ', entered password: ' . $password . ', hashed password: ' . $hashed_password);
     }     
    } else {
      $message[] = 'Email not found!';
      error_log('Email not found for email: ' . $email);
   }

    mysqli_stmt_close($select);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Login</h3>
      <?php
      if(isset($message)){
         foreach($message as $msg){
            echo '<div class="message">'.$msg.'</div>';
         }
      }
      ?>
      <input type="email" name="email" placeholder="Email" class="box" required autocomplete="username">
      <input type="password" name="password" placeholder="Password" class="box" required autocomplete="current-password">
      <input type="submit" name="submit" value="Login Now" class="btn">
      <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
      <p><a href="index.php">Homepage</a></p>
   </form>

</div>

</body>
</html>
