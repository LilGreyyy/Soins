<?php
include 'includes/dbh.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submitReview'])) {
        $productId = $_POST['productId'];
        $reviewContent = $_POST['reviewContent'];
        $rating = $_POST['rating'];
        $userId = 1; // Replace this with the actual user ID from your session or authentication system

        // Use prepared statements to prevent SQL injection
        $sql = "INSERT INTO productreview (productId, usersId, review_text, rating, review_date) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("iisi", $productId, $userId, $reviewContent, $rating);

            // Execute the statement
            if ($stmt->execute()) {
                // Redirect to the product detail page
                header("Location: product_detail.php?productId=$productId");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
