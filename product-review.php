
<?php
include 'includes/dbh.inc.php';
include_once 'blocks/header.php';
function addProductReview($productId, $reviewerName, $reviewText, $rating) {
    global $conn;

    $reviewDate = date("Y-m-d"); // Current date

    $sql = "INSERT INTO pReview (productId, reviewer_name, review_text, rating, review_date) 
            VALUES ('$productId', '$reviewerName', '$reviewText', '$rating', '$reviewDate')";

    if ($conn->query($sql) === TRUE) {
        echo "Review added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// to retrieve product reviews
function getProductReviews($productId) {
    global $conn;

    $sql = "SELECT * FROM pReview WHERE productId = '$productId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "Reviewer Name: " . $row["reviewer_name"]. "<br>";
            echo "Review Text: " . $row["review_text"]. "<br>";
            echo "Rating: " . $row["rating"]. "<br>";
            echo "Review Date: " . $row["review_date"]. "<br><br>";
        }
    } else {
        echo "No reviews for this product yet.";
    }
}

addProductReview(1, "John Doe", "This product is amazing!", 5);

echo "Product Reviews:<br>";
getProductReviews(1);
?>