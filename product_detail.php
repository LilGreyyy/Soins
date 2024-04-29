<?php
include 'includes/dbh.inc.php';
include_once 'blocks/header.php';

$uploadDirectory = "./admin/"; // Define the directory where your product images are stored

$sql = "SELECT productId, name, size, description, price, quantity, image FROM products";
$result = mysqli_query($conn, $sql);

// Step 2: Retrieve the product information
if (isset($_GET['productId'])) {
    $productId = $_GET['productId'];
    
    // Construct and execute a SQL query to fetch the product details
    $sql = "SELECT * FROM products WHERE productId = $productId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Step 3: Display the product information
        $row = $result->fetch_assoc();
        $productName = $row['name'];
        $productImage = $row['image'];
        $productSize = $row['size'];
        $productDescription = $row['description'];
        $productPrice = $row['price'];
        $productQuantity = $row['quantity'];
        
        // Display the product details on the page
        // Image sectionn
        echo "<section class='productSection'>";

        // Image section
        echo "<section class='imageSection'>";
        echo "<img class='productImg' src='$uploadDirectory$productImage' alt='$productName'>";
        echo "</section>";

        // Information section
        echo "<section class='infoSection'>";
        echo "<h1>$productName</h1>";
        echo "<p class='desc'>Description: $productDescription</p>";
        echo "<p class='sP'>Size: $productSize</p>";

        echo "</section>";

    } else {
        echo "Product not found.";
    }
} else {
    echo "Product ID not provided.";
}

// Step 4: Close the database connection
$conn->close();
?>
<link rel="stylesheet" type="text/css" href="css/productdetail.css">
<h2>Submit a Review</h2>
    <form action="submit_review.php" method="post">
        <input type="hidden" name="productId" value="1"> <!-- Assuming productId is 1 for the product being reviewed -->
        <label for="reviewer_name">Your Name:</label><br>
        <input type="text" id="reviewer_name" name="reviewer_name"><br>
        <label for="review_text">Your Review:</label><br>
        <textarea id="review_text" name="review_text"></textarea><br>
        <label for="rating">Rating (1-5):</label><br>
        <input type="number" id="rating" name="rating" min="1" max="5"><br>
        <input type="submit" value="Submit Review">
    </form>