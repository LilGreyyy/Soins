<!--<!doctype html>
<html lang="en-US">
    <head>
        <title>HTML5 Local Storage Project</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="rating" content="General">
        <meta name="expires" content="never">
        <meta name="language" content="English, EN">
        <meta name="description" content="Shopping cart project with HTML5 and JavaScript">
        <meta name="keywords" content="HTML5,CSS,JavaScript, html5 session storage, html5 local storage">
        <meta name="author" content="dcwebmakers.com">
        <script src="js/storage.js"></script>
        <link rel="stylesheet" href="storagestyle.css">
    </head>
<form name="ShoppingList">
    <fieldset>
        <legend>Shopping cart</legend>
        <label>Item: <input type="text" name="name"></label>
        <label>Quantity: <input type="text" name="data"></label>

        <input type="button" value="Save"   onclick="SaveItem()">
        <input type="button" value="Update" onclick="ModifyItem()">
        <input type="button" value="Delete" onclick="RemoveItem()">
    </fieldset>
    <div id="items_table">
        <h2>Shopping List</h2>
        <table id="list"></table>
        <label><input type="button" value="Clear" onclick="ClearAll()">
        * Delete all items</label>
    </div>
</form>-->
<?php
include('includes/dbh.inc.php');

if(isset($_SESSION['basket'])) {
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Name</th>';
    echo '<th>Price</th>';
    echo '<th>Quantity</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    $total_price = 0;

    foreach($_SESSION['basket'] as $product_id => $quantity) {
        $sql = "SELECT * FROM products WHERE id = '$product_id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        echo '<tr>';
        echo '<td>'.$row['name'].'</td>';
        echo '<td>$'.$row['price'].'</td>';
        echo '<td>'.$quantity.'</td>';
        echo '</tr>';

        $total_price += $row['price'] * $quantity;
    }

    echo '</tbody>';
    echo '<tfoot>';
    echo '<tr>';
    echo '<td colspan="2">Total price:</td>';
    echo '<td>$'.$total_price.'</td>';
    echo '</tr>';
    echo '</tfoot>';
    echo '</table>';
} else {
    echo "Your basket is empty";
}

mysqli_close($conn);
?>

