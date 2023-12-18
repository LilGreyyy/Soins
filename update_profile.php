<?php
include 'includes/dbh.inc.php';
session_start();
$user_id = $_SESSION['user_id'];

$select = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
$fetch = mysqli_fetch_assoc($select);

if(isset($_POST['update_profile'])){

   $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
   $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);

   mysqli_query($conn, "UPDATE `users` SET name = '$update_name', email = '$update_email' WHERE id = '$user_id'") or die('query failed');

   $old_pass_input = md5($_POST['old_pass']);
   $update_pass = password_hash($_POST['update_pass'], PASSWORD_DEFAULT);

   $new_pass = mysqli_real_escape_string($conn, $_POST['new_pass']);
   $confirm_pass = mysqli_real_escape_string($conn, $_POST['confirm_pass']);

   if (!empty($new_pass) && !empty($confirm_pass)) {
      if ($new_pass != $confirm_pass) {
         $message[] = 'Confirm password not matched!';
      } else {
         $hashed_new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
         mysqli_query($conn, "UPDATE `users` SET password = '$hashed_new_pass' WHERE id = '$user_id'") or die('query failed');
         $message[] = 'Password updated successfully!';
      }
   } elseif (!empty($new_pass) || !empty($confirm_pass)) {
      $message[] = 'Both new password and confirm password must be filled!';
   } else {
         $message[] = 'New password and confirm password must be filled!';
      }
   } else {
      $message[] = 'Old password not matched!';
   }

   $update_image = isset($_FILES['update_image']) ? $_FILES['update_image'] : null;

   if (!empty($update_image)) {
      $update_image_name = $update_image['name'];
      $update_image_size = $update_image['size'];
      $update_image_tmp_name = $update_image['tmp_name'];
      $update_image_folder = 'uploaded_img/' . $update_image_name;

      if ($update_image_size > 2000000) {
         $message[] = 'Image is too large';
      } else {
         $image_update_query = mysqli_query($conn, "UPDATE `users` SET image = '$update_image_name' WHERE id = '$user_id'") or die('query failed');
         if ($image_update_query) {
               move_uploaded_file($update_image_tmp_name, $update_image_folder);
         }
         $message[] = 'Image updated successfully!';
      }
   }


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update profile</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<div class="update-profile">

   <form action="" method="post" enctype="multipart/form-data">
      <?php
         if(isset($fetch) && $fetch['image'] == ''){
            echo '<img src="images/default-avatar.png">';
         } elseif(isset($fetch)) {
            echo '<img src="uploaded_img/'.$fetch['image'].'">';
         }
         if(isset($message)){
            foreach($message as $message){
               echo '<div class="message">'.$message.'</div>';
            }
         }
      ?>
      <div class="flex">
         <div class="inputBox">
            <span>Username :</span>
            <input type="text" name="update_name" value="<?php echo $fetch['name']; ?>" class="box">
            <span>Email :</span>
            <input type="email" name="update_email" value="<?php echo $fetch['email']; ?>" class="box">
            <span>Update your pic :</span>
            <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
         </div>
         <div class="inputBox">
            <input type="hidden" name="old_pass" value="<?php echo $fetch['password']; ?>">
            <span>Old password :</span>
            <input type="password" name="update_pass" placeholder="Previous password" class="box">
            <span>New password :</span>
            <input type="password" name="new_pass" placeholder="New password" class="box">
            <span>Confirm password :</span>
            <input type="password" name="confirm_pass" placeholder="Confirm new password" class="box">
         </div>
      </div>
      <input type="submit" value="Update profile" name="update_profile" class="btn">
      <a href="profile.php" class="delete-btn">Go back</a>
   </form>

</div>

</body>
</html>