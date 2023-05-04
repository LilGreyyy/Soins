<?php
    include_once 'blocks/authheader.php';
    
    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "soins");

    // Get all products from the database
    $sql = "SELECT * FROM products";
    $result = mysqli_query($conn, $sql);

    // Close the database connection
    mysqli_close($conn);
?>
<body>
    <div class="shop-main">
        <?php
            // Display each product on the page
            while($row = mysqli_fetch_assoc($result)) {
            echo "<div class='product'>";
            echo "<h3>".$row['name']."</h3>";
            echo "<p>".$row['size']."</p>";
            echo "<p>Price: $".$row['price']."</p>";
            echo "<a href='basket.php?productId=".$row['productId']."'>Add to Cart</a>";
            echo "</div>";
            }
        ?>
    </div>
</body>