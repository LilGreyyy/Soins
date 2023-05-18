<?php
require_once "../includes/dbh.inc.php";
include_once "adminheader.php";
// Check if product ID is provided in URL
$product_id = isset($_GET['productId']) ? $_GET['productId'] : '';

// Prepare the SQL statement with a placeholder for the product ID
$sql = "SELECT * FROM `products` WHERE productId = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);

// Get the result set
$result = mysqli_stmt_get_result($stmt);

// If the product exists, display the product information in a form
if (mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
?>
<link href="css/update.css" rel="stylesheet">
<div class="blnk"></div>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['productId']); ?>">
  <label for="product_name">Product Name:</label>
  <input type="text" name="product_name" value="<?php echo htmlspecialchars($row['name']); ?>">
  <label for="product_size">Product Size:</label>
  <input type="text" name="product_size" value="<?php echo htmlspecialchars($row['size']); ?>">
  <label for="product_description">Product Description:</label>
  <textarea name="product_description"><?php echo htmlspecialchars($row['description']); ?></textarea>
  <label for="product_price">Product Price:</label>
  <input type="number" name="product_price" value="<?php echo htmlspecialchars($row['price']); ?>">
  <label for="product_quantity">Product Quantity:</label>
  <input type="number" name="product_quantity" value="<?php echo htmlspecialchars($row['quantity']); ?>">
  <input type="submit" value="Update">
</form>
<div class="blnk"></div>
<?php
} else {
  echo "Product not found.";
}

// Close the prepared statement and the database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
