<?php
require_once "../includes/dbh.inc.php";

if (!$conn) {
  die("Connect error: " . mysqli_connect_error());
}

// Check if the productId parameter is provided
if (isset($_GET['productId'])) {
  $productId = $_GET['productId'];

  // Delete the product from the database
  $sql = "DELETE FROM products WHERE productId = $productId";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    header('Location: storeupdate.php');
  } else {
    echo "Error deleting product: " . mysqli_error($conn);
  }
} else {
  echo "Invalid request. Product ID not provided.";
}

// Closing the database connection
mysqli_close($conn);
?>
