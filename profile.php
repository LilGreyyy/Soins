<?php
include_once 'blocks/header.php';
include 'includes/dbh.inc.php';

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:login.php');
}

$consultations_query = mysqli_query($conn, "SELECT consultations.*, categories.categoryName AS master_category, masters.mFullName, consultations.type
                                            FROM `consultations`
                                            INNER JOIN `categories_masters` ON consultations.masterId = categories_masters.masterId
                                            INNER JOIN `categories` ON categories_masters.categoryId = categories.categoryId
                                            INNER JOIN `masters` ON consultations.masterId = masters.masterId
                                            WHERE consultations.usersId = '$user_id'
                                            ORDER BY consultations.cons_date DESC, consultations.cons_time DESC") or die('Query failed: ' . mysqli_error($conn));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
<div class="pcontainer">
    <div class="profile">
        <?php
        $select = mysqli_query($conn, "SELECT * FROM `users` WHERE usersId = '$user_id'") or die('query failed');
        if (mysqli_num_rows($select) > 0) {
            $fetch = mysqli_fetch_assoc($select);
        }
        if ($fetch['image'] == '') {
            echo '<img src="images/default-avatar.png">';
        } else {
            echo '<img src="uploaded_img/'.$fetch['image'].'">';
        }
        ?>
        <h3><?php echo $fetch['name']; ?></h3>
        <p>Email: <?php echo $fetch['email']; ?></p>
        <p>Phone Number: <?php echo $fetch['number']; ?></p>
        <a href="update_profile.php" class="btn">Veikt izmaiņas</a>
        <a href="profile.php?logout=<?php echo $user_id; ?>" class="btn">Iziet</a>
        <a href="deleteuser.php?id=<?php echo $user_id; ?>" class="delete-btn">Dzēst profilu</a>
    </div>
    <div class="consultations-container">
        <h2>Jūsu konsultācijas</h2>
        <table>
            <tr>
                <th>Laiks</th>
                <th>Datums</th>
                <th>Statuss</th>
                <th>Kategorija</th>
                <th>Meistars</th>
                <th>Veids</th>
                <th>Lejupielādēt PDF</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($consultations_query)) {
                echo "<tr>";
                echo "<td>".$row['cons_date']."</td>";
                echo "<td>".$row['cons_time']."</td>";
                echo "<td>";
               if ($row['status'] == 'Pieteikts') {
                  echo "<span class='success'>Apstiprināts</span>";
               } elseif ($row['status'] == 'Atcelts') {
                  echo "<span class='error'>".$row['status']."</span>";
               } else {
                  echo $row['status'];
               }
               echo "</td>";
                echo "<td>".$row['master_category']."</td>";
                echo "<td>".$row['mFullName']."</td>";
                echo "<td>".$row['type']."</td>";
                echo "<td><a href='download_pdf.php?cons_id=".$row['cons_id']."' class='btn'>Lejupielādēt PDF</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>
