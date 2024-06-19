<?php
require_once "../includes/dbh.inc.php";
include_once "adminheader.php";

// Check if product ID is provided in URL
$product_id = isset($_GET['productId']) ? $_GET['productId'] : '';

// Prepare the SQL statement with a placeholder for the product ID
$sql = "SELECT * FROM `products` WHERE `productId` = ?";
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
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
  <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['productId']); ?>">
  <label for="product_name">Product Name:</label>
  <input type="text" name="product_name" value="<?php echo htmlspecialchars($row['name']); ?>">
  <label for="product_size">Product Size:</label>
  <input type="text" name="product_size" value="<?php echo htmlspecialchars($row['size']); ?>">
  <label for="product_description">Product Description:</label>
  <textarea name="product_description"><?php echo htmlspecialchars($row['description']); ?></textarea>
  <label for="product_price">Product Price:</label>
  <input type="text" name="product_price" value="<?php echo htmlspecialchars($row['price']); ?>">
  <label for="product_image">Product Image:</label>
  <input type="file" name="product_image">
  <input type="submit" value="Update" name="update_product">
</form>
<div class="blnk"></div>
<?php
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_product"])) {
  if (isset($_POST["product_id"])) {
    $product_id = $_POST["product_id"];
    $product_name = $_POST["product_name"];
    $product_size = $_POST["product_size"];
    $product_description = $_POST["product_description"];
    $product_price = validateAndConvertPrice($_POST["product_price"]);

    // Handle image upload only if a file was selected
    if (!empty($_FILES["product_image"]["name"])) {
      $uploadDirectory = "uploads/";
      $uploadedFile = $_FILES["product_image"]["tmp_name"];
      $product_image = $uploadDirectory . $_FILES["product_image"]["name"];

      if ($_FILES["product_image"]["error"] !== UPLOAD_ERR_OK) {
        echo "Error uploading the image. Error code: " . $_FILES["product_image"]["error"];
        exit; // Exit the script to prevent further execution
      }

      if (!move_uploaded_file($uploadedFile, $product_image)) {
        echo "Error moving the uploaded file.";
        exit;
      }
    } else {
      $product_image = NULL; // Set to NULL if no new image is uploaded
    }

    // Update the product information in the database, including the image file path
    $sql = "UPDATE `products` SET `name` = ?, `size` = ?, `description` = ?, `price` = ?, `image` = ? WHERE `productId` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssdsi", $product_name, $product_size, $product_description, $product_price, $product_image, $product_id);

    if (mysqli_stmt_execute($stmt)) {
      echo "Product updated successfully.";
    } else {
      echo "Error updating product: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
  }
}

// Function to validate and convert the product price
function validateAndConvertPrice($price) {
  // Remove any non-numeric characters and then convert to a float
  $cleaned_price = filter_var($price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  return floatval($cleaned_price);
}

// Close the prepared statement and the database connection
mysqli_close($conn);
?>
