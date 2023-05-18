<?php
include_once 'blocks/authheader.php';
include 'includes/dbh.inc.php';

// Start the session
session_start();

// Check if a product_id is provided
if (isset($_GET['product_id'])) {
    // Get the product_id from the URL
    $product_id = $_GET['product_id'];

    // Retrieve the product details from the database
    $sql = "SELECT * FROM products WHERE productId = $product_id";
    $result = mysqli_query($conn, $sql);

    // Check if the product exists
    if (mysqli_num_rows($result) > 0) {
        // Fetch the product details
        $product = mysqli_fetch_assoc($result);

        // Add the product to the basket
        $usersId = $_SESSION['usersId']; // Assuming you have stored the userId in a session variable
        $totalItems = 1; // Assuming you always add one item at a time
        $totalAmount = $product['price']; // Assuming the total amount is the same as the product price

        // Insert the product into the basket table
        $insertQuery = "INSERT INTO basket (usersId, productId, totalItems, totalAmount) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "iiid", $usersId, $product_id, $totalItems, $totalAmount);
        mysqli_stmt_execute($stmt);

        // Display a success message
        echo "Product added to basket: " . $product['name'];
    } else {
        // Product not found
        echo "Product not found.";
    }
} else {
    // No product_id provided
    echo "No product selected.";
}
?>
