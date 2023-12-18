<?php
include_once 'blocks/header.php';
include 'includes/dbh.inc.php';

// Debugging
var_dump($_SESSION);

// Check if session not started for this user
if (!isset($_SESSION['user_id'])) {
    echo "User ID not found in session.";
    die(); // Stop execution
}

$user_id = $_SESSION['user_id'];

// Before checking for productId
if (isset($_GET['productId'])) {
    $productId = $_GET['productId'];

    // Get data from the database
    $sql = "SELECT * FROM products WHERE productId = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if the product exists
    if (mysqli_num_rows($result) > 0) {
        // Fetch the product details
        $product = mysqli_fetch_assoc($result);

        // Check if already exists in basket
        $checkQuery = "SELECT * FROM basket WHERE usersId = ? AND productId = ?";
        $checkStmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "ii", $user_id, $productId);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);

        if (mysqli_num_rows($checkResult) > 0) {
            // If exists, update
            // ... (your update code)
        } else {
            // New
            // ... (your insert code)
        }

        // Close the statements
        mysqli_stmt_close($checkStmt);
        mysqli_stmt_close($stmt);
    } else {
        echo "Product not found.";
    }

    // Close database connection (optional)
    mysqli_close($conn);
}
?>
