<?php
include 'includes/dbh.inc.php';
include_once 'blocks/header.php';

$uploadDirectory = "./admin/"; // Definējiet katalogu, kur glabājas produktu attēli

// Solis 1: Izgūstiet informāciju par produktu
if (isset($_GET['productId'])) {
    $productId = $_GET['productId'];
    
    // Izveidojiet un izpildiet SQL vaicājumu, lai izgūtu detaļas par produktu
    $sql = "SELECT * FROM products WHERE productId = $productId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Solis 2: Parādiet informāciju par produktu
        $row = $result->fetch_assoc();
        $productName = $row['name'];
        $productImage = $row['image'];
        $productSize = $row['size'];
        $productDescription = $row['description'];
        $productPrice = $row['price'];
        
        // Parādiet detaļas par produktu uz lapas
        // Attēla sadaļa
        echo "<section class='productSection'>";

        // Attēla sadaļa
        echo "<section class='imageSection'>";
        echo "<img class='productImg' src='$uploadDirectory$productImage' alt='$productName'>";
        echo "</section>";

        // Informācijas sadaļa
        echo "<section class='infoSection'>";
        echo "<h1>$productName</h1>";
        echo "<p class='price'>Cena: $productPrice</p>";
        echo "<p class='desc'>$productDescription</p>"; // Parādīt aprakstu bez pogas

        echo "</section>";

        echo "</section>";

        // Parādiet esošās atsauksmes par produktu
        echo "<section class='reviewsSection'>";
        echo "<h2>Atsauksmes</h2>";
        $sql_reviews = "SELECT productreview.*, users.name AS reviewer_name
                        FROM productreview
                        INNER JOIN users ON productreview.usersId = users.usersId
                        WHERE productreview.productId = $productId"; // Tabulas nosaukums mainīts uz 'productreview'
        $result_reviews = $conn->query($sql_reviews);

        if ($result_reviews->num_rows > 0) {
            while ($row_review = $result_reviews->fetch_assoc()) {
                $reviewContent = $row_review['review_text'];
                $reviewerName = $row_review['reviewer_name'];
                $reviewRating = $row_review['rating'];
                $reviewDate = $row_review['review_date'];
                echo "<div class='review'>";
                echo "<p>Recenzents: $reviewerName</p>";
                echo "<p>Vērtējums: ";
                for ($i = 0; $i < $reviewRating; $i++) {
                    echo "★"; // Выводим рейтинг в виде звёздочек
                }
                echo "</p>";
                echo "<p>Recenzijas datums: $reviewDate</p>";
                echo "<p>Recenzija: $reviewContent</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Vēl nav atsauksmju.</p>";
        }
        echo "</section>";

        // Atsauksmju sadaļa
        echo "<section class='reviewSection'>";
        echo "<h2>Atstājiet atsauksmi</h2>";
        echo "<form action='submit_review.php' method='post'>";
        echo "<input type='hidden' name='productId' value='$productId'>";
        echo "<textarea name='reviewContent' placeholder='Šeit ierakstiet savu atsauksmi...' required></textarea>";
        echo "<label for='reviewRating'>Izvēlieties vērtējumu (1-5): </label>";
        echo "<input type='number' name='reviewRating' id='reviewRating' min='1' max='5' required>";
        echo "<button type='submit' name='submitReview' class='submitReviewBtn'>Pievienot atsauksmi</button>";
        echo "</form>";
        echo "</section>";

    } else {
        echo "Produkts nav atrasts.";
    }
} else {
    echo "Nav norādīts produkta ID.";
}

// Solis 3: Aizveriet datu bāzes savienojumu
$conn->close();
?>

<!DOCTYPE html>
<html lang="lv">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Produkta detaļas</title>
   <link rel="stylesheet" type="text/css" href="css/productdetail.css">
</head>
<body>
</body>
</html>
