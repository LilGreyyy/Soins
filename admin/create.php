<?php
require_once "../includes/dbh.inc.php"; // Include the database connection file
include_once "adminheader.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $size = $_POST["size"];
    $description = $_POST["description"];
    $price = $_POST["price"];

    $imagePath = uploadImage(); // Function to upload the image and return its path

    if ($imagePath !== false) {
        $price = convertPriceToFloat($price);

        $sql = "INSERT INTO products (name, size, description, price, image) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssd", $name, $size, $description, $price, $imagePath);

            if (mysqli_stmt_execute($stmt)) {
                $product_id = mysqli_insert_id($conn);
                mysqli_stmt_close($stmt);
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }

        mysqli_close($conn);
    } else {
        echo "Error uploading the image.";
    }
}

function convertPriceToFloat($price) {
    $cleaned_price = str_replace(',', '.', $price);
    return floatval($cleaned_price);
}

function uploadImage() {
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        $targetDirectory = "uploads/";
        $imageName = uniqid() . "_" . basename($_FILES["image"]["name"]);
        $imagePath = $targetDirectory . $imageName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
            return $imagePath;
        }
    }
    return false;
}
?>

<link href="css/update.css" rel="stylesheet">
<div class="blnk"></div>
<form method="POST" action="" enctype="multipart/form-data">
    <label for="image">Image:</label>
    <input type="file" name="image" accept="image/*"><br>

    <label for="name">Name:</label>
    <input type="text" name="name" required><br>

    <label for="size">Size:</label>
    <input type="text" name="size" required><br>

    <label for="description">Description:</label>
    <textarea name="description" required></textarea><br>

    <label for="price">Price:</label>
    <input type="text" name="price" required><br>

    <input type="submit" value="Create Product">
</form>
<div class="blnk"></div>
