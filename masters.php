<!DOCTYPE html>
<html lang="ru">
<?php include_once 'blocks/header.php'; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/css/masters.css">
    <link href='https://fonts.googleapis.com/css?family=Merriweather' rel='stylesheet'>
</head>
<body>
    <div>
        <section class="aboutmasters">
            <a></a>
        </section>
        <section class="m1">
            <?php
            // Вывод информации о мастерах
            include 'includes/dbh.inc.php';
            $sql = "SELECT m.masterId, m.mFullName, m.mPhoto, c.categoryName, 
            DATE_FORMAT(m.workTimeOpen, '%H:%i') AS workTimeOpen, 
            DATE_FORMAT(m.workTimeClose, '%H:%i') AS workTimeClose
            FROM masters AS m
            INNER JOIN categories_masters AS cm ON m.masterId = cm.masterId
            INNER JOIN categories AS c ON cm.categoryId = c.categoryId
            GROUP BY m.masterId
            ORDER BY m.mFullName";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $mFullName = $row['mFullName'];
                    $mPhoto = $row['mPhoto'];
                    $categoryName = $row['categoryName'];
                    $workTimeOpen = $row['workTimeOpen'];
                    $workTimeClose = $row['workTimeClose'];

                    $imagePath = "../admin/masteruploads/" . basename($mPhoto);

                    echo "<div class='master'>
                            <img src='$imagePath' alt='$mFullName'>
                            <h2>$mFullName</h2>
                            <p>$categoryName</p>
                            <p>Darba laiks: $workTimeOpen-$workTimeClose</p>
                          </div>";
                }
            } else {
                echo "No masters found.";
            }
            $conn->close();
            ?>
        </section>
    </div>
</body>
</html>
