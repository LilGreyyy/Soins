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

  // Handle file upload
  $imagePath = uploadImage(); // Function to upload the image and return its path

  if ($imagePath !== false) {
    // Convert the price to a valid format (float)
    $price = convertPriceToFloat($price);

    // Insert the product into the database with the image path
    $sql = "INSERT INTO products (name, size, description, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($conn, $sql)) {
      // Bind the parameters
      mysqli_stmt_bind_param($stmt, "ssssds", $name, $size, $description, $price, $quantity, $imagePath);

      // Execute the statement
      if (mysqli_stmt_execute($stmt)) {
        // Get the ID of the newly inserted product
        $product_id = mysqli_insert_id($conn);

        // Close the statement
        mysqli_stmt_close($stmt);
      } else {
        echo "Error: " . mysqli_error($conn);
      }
    } else {
      echo "Error: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
  } else {
    echo "Error uploading the image.";
  }
}

// Function to convert the input price to a float
function convertPriceToFloat($price) {
  // Replace any commas with dots
  $cleaned_price = str_replace(',', '.', $price);
  // Convert to a float
  return floatval($cleaned_price);
}

// Function to handle file upload and return the image path
function uploadImage() {
  // Check if a file was uploaded
  if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
    $targetDirectory = "uploads/"; // Specify the directory where you want to store uploaded images

    // Generate a unique file name to avoid overwriting
    $imagePath = $targetDirectory . uniqid() . "_" . $_FILES["image"]["name"];

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
      return $imagePath;
    }
  }

  return false; // Return false if there was an error or no file was uploaded
}
?>

<link href="css/update.css" rel="stylesheet">
<div class="blnk"></div>
<!-- HTML form to enter the product details -->
<form method="POST" action="" enctype="multipart/form-data">
  <label for="image">Image:</label>
  <input type="file" name="image" accept="image/*">

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