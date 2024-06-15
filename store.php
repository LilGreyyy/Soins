<?php
include 'includes/dbh.inc.php';
include_once 'blocks/header.php';

$uploadDirectory = "./admin/"; // Define the directory where your product images are stored

// Retrieve products from the database
$sql = "SELECT productId, name, size, description, price, image FROM products";
$result = mysqli_query($conn, $sql);

?>

<link rel="stylesheet" href="css/store.css">

<body>
    <div class="shop-main">
        <?php
        // Loop through each product and display it in a div
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='product'>";
            if (isset($row['image'])) {
                echo "<img src='" . $uploadDirectory . '/' . $row['image'] . "' alt='Product Image' width='250'>";
            } else {
                echo "Bilde nav pieejama"; // Handle the case when 'image' is not set
            }            
                echo "<div class='productTxt'>";
                echo "<h3><a href='product_detail.php?productId=" . $row['productId'] . "'>" . $row['name'] . "</a></h3>";
                // Check if 'image_path' exists in the $row array
                echo "<p>" . $row['size'] . "</p>";
                echo "<p>Price: $" . $row['price'] . "</p>";
                echo "</div>";
            echo "</div>";
        }
        ?>
    </div>
</body>