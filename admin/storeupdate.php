<?php
include "adminheader.php";
?>
<?php
require_once "../includes/dbh.inc.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!$conn) {
  die("Connect error: " . mysqli_connect_error());
}

$uploadDirectory = "./uploads";
chmod($uploadDirectory, 0755);

// Fetching product information from database
$sql = "SELECT productId, name, size, description, price, quantity, image FROM products";
$result = mysqli_query($conn, $sql);

// Checking for results
if (mysqli_num_rows($result) > 0) {
  // Data output from table HTML
  echo "<table>
  <tr>
  <th>ID</th>
  <th>Image</th>
  <th>Name</th>
  <th>Size</th>
  <th>Description</th>
  <th>Price</th>
  <th>Quantity</th>
  <th>Update</th>
  <th>Delete</th>
  </tr>";
  while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
    <td>".$row["productId"]."</td>
    <td><img src='".$row["image"]."' alt='Product Image' width='100'></td>
    <td>".$row["name"]."</td>
    <td>".$row["size"]."</td>
    <td>".$row["description"]."</td>
    <td>$".$row["price"]."</td>
    <td class='quantity'>".$row["quantity"]."</td>
    <td><button class='tbb' onclick=\"window.location.href='update.php?productId=".$row["productId"]."'\">Update</button></td>
    <td><button class='tbb' onclick=\"deleteProduct(".$row["productId"].")\">Delete</button></td>
    </tr>";
  }
  echo "</table>";
} else {
  echo "No result";
}

// Closing the database connection
mysqli_close($conn);
?>

<!-- Add a "Create Product" button -->
<div class="create-product-button">
  <button onclick="window.location.href='create.php'">Create Product</button>
</div>

<link href="css/storeupdate.css" rel="stylesheet">

<script>
// JavaScript function to handle the product deletion
function deleteProduct(productId) {
  if (confirm("Are you sure you want to delete this product?")) {
    // Redirect to the delete page or perform an AJAX request to delete the product
    window.location.href = "delete.php?productId=" + productId;
  }
}
</script>
