<?php
include 'includes/dbh.inc.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$select = mysqli_query($conn, "SELECT * FROM `users` WHERE usersId = '$user_id'") or die('Query failed: ' . mysqli_error($conn));
$fetch = mysqli_fetch_assoc($select);

if (isset($_POST['update_profile'])) {
    $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
    $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);
    $update_number = mysqli_real_escape_string($conn, $_POST['update_number']); // Add this line for updating the phone number

    // Update name, email, and number
    mysqli_query($conn, "UPDATE `users` SET name = '$update_name', email = '$update_email', number = '$update_number' WHERE usersId = '$user_id'") or die('Query failed: ' . mysqli_error($conn));

    $old_pass_input = mysqli_real_escape_string($conn, $_POST['update_pass']);
    $new_pass = mysqli_real_escape_string($conn, $_POST['new_pass']);
    $confirm_pass = mysqli_real_escape_string($conn, $_POST['confirm_pass']);

    // Fetch the current password from the database
    $result = mysqli_query($conn, "SELECT password FROM `users` WHERE usersId = '$user_id'") or die('Query failed: ' . mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);
    $current_password_hash = $row['password'];

    // Verify old password
    if (!empty($old_pass_input) && password_verify($old_pass_input, $current_password_hash)) {
        if (!empty($new_pass) && !empty($confirm_pass)) {
            if ($new_pass != $confirm_pass) {
                $message[] = 'Confirm password not matched!';
            } else {
                // Validate new password strength
                if (strlen($new_pass) < 8 || !preg_match("#[0-9]+#", $new_pass) || !preg_match("#[A-Z]+#", $new_pass) || !preg_match("#[a-z]+#", $new_pass) || !preg_match("#[\W]+#", $new_pass)) {
                    $message[] = 'New password does not meet the criteria.';
                } else {
                    $hashed_new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
                    mysqli_query($conn, "UPDATE `users` SET password = '$hashed_new_pass' WHERE usersId = '$user_id'") or die('Query failed: ' . mysqli_error($conn));
                    $message[] = 'Password updated successfully!';
                }
            }
        } elseif (!empty($new_pass) || !empty($confirm_pass)) {
            $message[] = 'Both new password and confirm password must be filled!';
        }
    } else {
        if (!empty($old_pass_input)) {
            $message[] = 'Old password not matched!';
        }
    }

    // Image update logic
    if (isset($_FILES['update_image']) && $_FILES['update_image']['error'] === UPLOAD_ERR_OK) {
        $update_image_name = $_FILES['update_image']['name'];
        $update_image_size = $_FILES['update_image']['size'];
        $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
        $update_image_folder = 'uploaded_img/' . $update_image_name;

        if ($update_image_size > 2000000) {
            $message[] = 'Image is too large';
        } else {
            $image_update_query = mysqli_query($conn, "UPDATE `users` SET image = '$update_image_name' WHERE usersId = '$user_id'") or die('Query failed: ' . mysqli_error($conn));
            if ($image_update_query) {
                move_uploaded_file($update_image_tmp_name, $update_image_folder);
                $message[] = 'Image updated successfully!';
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
   <title>Update Profile</title>
   <link rel="stylesheet" href="css/update_profile.css">
</head>
<body>
<div class="update-profile">
   <form action="" method="post" enctype="multipart/form-data">
      <?php
         if (isset($fetch) && $fetch['image'] == '') {
            echo '<img src="images/default-avatar.png">';
         } elseif (isset($fetch)) {
            echo '<img src="uploaded_img/' . $fetch['image'] . '">';
         }
         if (isset($message)) {
            foreach ($message as $msg) {
               echo '<div class="message">' . $msg . '</div>';
            }
         }
      ?>
      <div class="flex">
         <div class="inputBox">
            <span>Vārds:</span>
            <input type="text" name="update_name" value="<?php echo htmlspecialchars($fetch['name']); ?>" class="box">
            <span>E-pasts:</span>
            <input type="email" name="update_email" value="<?php echo htmlspecialchars($fetch['email']); ?>" class="box">
            <span>Telefona numurs:</span> <!-- Add this line -->
            <input type="text" name="update_number" value="<?php echo htmlspecialchars($fetch['number']); ?>" class="box"> <!-- Add this line -->
            <span>Bilde:</span>
            <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
         </div>
         <div class="inputBox">
            <span>Vecā parole:</span>
            <input type="password" name="update_pass" placeholder="Vecā parole" class="box">
            <span>Jaunā parole:</span>
            <input type="password" name="new_pass" placeholder="Jaunā parole" class="box">
            <span>Atkārtot jauno paroli:</span>
            <input type="password" name="confirm_pass" placeholder="Jaunā parole" class="box">
         </div>
      </div>
      <div class="btn-container">
         <input type="submit" value="Veikt izmaiņas" name="update_profile" class="btn">
         <a href="profile.php" class="delete-btn">Atpakaļ</a>
      </div>
   </form>
</div>
</body>
</html>
