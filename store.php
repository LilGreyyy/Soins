<?php
include 'includes/dbh.inc.php';
include_once 'blocks/authheader.php';
// Retrieve products from the database
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);

?>

<link rel="stylesheet" href="css/store.css">

<body>
    <div class="shop-main">
        <?php
        // Loop through each product and display it in a div
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='product'>";
            echo "<h3>" . $row['name'] . "</h3>";
            echo "<p>" . $row['size'] . "</p>";
            echo "<p>Price: $" . $row['price'] . "</p>";
            echo "<a href='basket.php?product_id=" . $row['productId'] . "'>Add to Cart</a>";
            echo "</div>";
        }
        ?>
    </div>
</body>
