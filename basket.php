<?php
include_once 'blocks/authheader.php';
include 'includes/dbh.inc.php';

// Start the session
session_start();

$userId = $_SESSION['usersId']; // Retrieve the user's ID from the session

// Check if a product_id is provided
if (isset($_GET['product_id'])) {
    // Get the product_id from the URL
    $product_id = $_GET['product_id'];

    // Retrieve the product details from the database
    $sql = "SELECT * FROM products WHERE productId = ?";
    $result = mysqli_query($conn, $sql);

    // Check if the product exists
    if (mysqli_num_rows($result) > 0) {
        // Fetch the product details
        $product = mysqli_fetch_assoc($result);

        // Check if the record already exists in the basket
        $checkQuery = "SELECT * FROM basket WHERE usersId = ? AND productId = ?";
        $checkStmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "ii", $userId, $product_id);
        mysqli_stmt_execute($checkStmt);

        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            // Record already exists, you should update it
            $updateQuery = "UPDATE basket SET totalItems = totalItems + 1, totalAmount = totalAmount + ? WHERE usersId = ? AND productId = ?";
            $updateStmt = mysqli_prepare($conn, $updateQuery);
            $totalAmount = $product['price'];
            mysqli_stmt_bind_param($updateStmt, "dii", $totalAmount, $userId, $product_id);
            mysqli_stmt_execute($updateStmt);

            // Display a success message
            echo "Product updated in the basket: " . $product['name'];
        } else {
            // Insert the new record
            $totalItems = 1; // Assuming you always add one item at a time
            $insertQuery = "INSERT INTO basket (usersId, productId, totalItems, totalAmount) VALUES (?, ?, ?, ?)";
            $insertStmt = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($insertStmt, "iiid", $userId, $product_id, $totalItems, $totalAmount);
            mysqli_stmt_execute($insertStmt);

            // Display a success message
            echo "Product added to the basket: " . $product['name'];
        }
    } else {
        // Product not found
        echo "Product not found.";
    }
} else {
    // No product_id provided
    echo "No product selected.";
}
?>