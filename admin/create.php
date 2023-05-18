<?php
require_once "../includes/dbh.inc.php"; // Include the database connection file

include_once "adminheader.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve the product details from the form
  $name = $_POST["name"];
  $size = $_POST["size"];
  $description = $_POST["description"];
  $price = $_POST["price"];
  $quantity = $_POST["quantity"];

  // Insert the product into the database
  $sql = "INSERT INTO products (name, size, description, price, quantity) VALUES ('$name', '$size', '$description', '$price', '$quantity')";
  if (mysqli_query($conn, $sql)) {
    header('Location: storeupdate.php');
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }

  // Close the database connection
  mysqli_close($conn);
}
?>
<link href="css/update.css" rel="stylesheet">
<div class="blnk"></div>
<!-- HTML form to enter the product details -->
<form method="POST" action="">
  <label for="name">Name:</label>
  <input type="text" name="name" required><br>

  <label for="size">Size:</label>
  <input type="text" name="size" required><br>

  <label for="description">Description:</label>
  <textarea name="description" required></textarea><br>

  <label for="price">Price:</label>
  <input type="text" name="price" required><br>

  <label for="quantity">Quantity:</label>
  <input type="number" name="quantity" required><br>

  <input type="submit" value="Create Product">
</form>
<div class="blnk"></div>