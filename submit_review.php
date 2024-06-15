<?php
include 'includes/dbh.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submitReview'])) {
        $productId = $_POST['productId'];
        $reviewContent = $_POST['reviewContent'];
        $rating = $_POST['rating']; // Добавлено получение рейтинга от пользователя

        // Вставка отзыва в таблицу productreview
        $sql = "INSERT INTO productreview (productId, review_text, rating) VALUES ('$productId', '$reviewContent', '$rating')";
        if ($conn->query($sql) === TRUE) {
            header("Location: product_detail.php?productId=$productId");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
