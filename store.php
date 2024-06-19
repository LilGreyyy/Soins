<?php
include 'includes/dbh.inc.php';
include_once 'blocks/header.php';

$uploadDirectory = "./admin/";

$keyword = '';
$brandKeyword = '';
$sortOrder = 'ASC';
$maxPrice = 0; // Initialize $maxPrice

// Fetch maxPrice from database
$priceResult = mysqli_query($conn, "SELECT MAX(price) AS maxPrice FROM products");
$priceRow = mysqli_fetch_assoc($priceResult);
if ($priceRow['maxPrice']) {
    $maxPrice = $priceRow['maxPrice'];
}

if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
}

if (isset($_GET['brandKeyword'])) {
    $brandKeyword = $_GET['brandKeyword'];
}

if (isset($_GET['sortOrder'])) {
    $sortOrder = $_GET['sortOrder'] == 'DESC' ? 'DESC' : 'ASC';
}

$sql = "SELECT productId, name, size, description, price, image FROM products WHERE 1=1";

if (!empty($keyword)) {
    $keyword = mysqli_real_escape_string($conn, $keyword);
    if ($keyword == "Sejas kopšanai") {
        $sql .= " AND description LIKE '%sejas ādai%'";
    } elseif ($keyword == "Matu kopšanai") {
        $sql .= " AND description LIKE '%matiem%'";
    }
}

if (!empty($brandKeyword)) {
    $brandKeyword = mysqli_real_escape_string($conn, $brandKeyword);
    $sql .= " AND name LIKE '%$brandKeyword%'";
}

$sql .= " ORDER BY price $sortOrder";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин</title>
    <link rel="stylesheet" href="css/store.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.7.0/nouislider.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.7.0/nouislider.min.js"></script>
</head>
<body>
    <div class="shop-main">
        <div class="sort-container">
            <button onclick="toggleSortCriteria()">Filtrēšana</button>
            <div id="sortCriteria" style="display:none;">
                <form method="GET" action="">
                    <!-- Price filters removed -->
                    <div class="filter-option">
                        <label for="keyword">Kategorija:</label>
                        <select name="keyword" id="keyword">
                            <option value="">Viss</option>
                            <option value="Sejas kopšanai" <?php if ($keyword == "Sejas kopšanai") echo 'selected="selected"'; ?>>Sejas ādai</option>
                            <option value="Matu kopšanai" <?php if ($keyword == "Matu kopšanai") echo 'selected="selected"'; ?>>Matu kopšanai</option>
                            <option value="Protect Lipstick" <?php if ($keyword == "Protect Lipstick") echo 'selected="selected"'; ?>>Lūpām</option>
                        </select>
                    </div>

                    <div class="filter-option">
                        <label for="brandKeyword">Zīmols:</label>
                        <select name="brandKeyword" id="brandKeyword">
                            <option value="">Viss</option>
                            <option value="The Ordinary" <?php if ($brandKeyword == "The Ordinary") echo 'selected="selected"'; ?>>The Ordinary</option>
                            <option value="one. two. free!" <?php if ($brandKeyword == "one. two. free!") echo 'selected="selected"'; ?>>one. two. free!</option>
                            <option value="LA'DOR" <?php if ($brandKeyword == "LA'DOR") echo 'selected="selected"'; ?>>LA'DOR</option>
                            <option value="Bioderma" <?php if ($brandKeyword == "Bioderma") echo 'selected="selected"'; ?>>Bioderma</option>
                            <option value="Dzintars" <?php if ($brandKeyword == "Dzintars") echo 'selected="selected"'; ?>>Dzintars</option>
                            <option value="Skin 1004" <?php if ($brandKeyword == "Skin 1004") echo 'selected="selected"'; ?>>Skin 1004</option>
                            <option value="LA ROCHE-POSAY" <?php if ($brandKeyword == "LA ROCHE-POSAY") echo 'selected="selected"'; ?>>LA ROCHE-POSAY</option>
                        </select>
                    </div>

                    <div class="filter-option">
                        <label for="sortOrder">Kārtot pēc cenas:</label>
                        <select name="sortOrder" id="sortOrder">
                            <option value="ASC" <?php if ($sortOrder == "ASC") echo 'selected="selected"'; ?>>Augošā</option>
                            <option value="DESC" <?php if ($sortOrder == "DESC") echo 'selected="selected"'; ?>>Dilstošā</option>
                        </select>
                    </div>

                    <button type="submit">Filtrēt</button>
                </form>
            </div>
        </div>

        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='product'>";
            if (isset($row['image']) && !empty($row['image'])) {
                echo "<img src='" . $uploadDirectory . $row['image'] . "' alt='Product Image' width='250'>";
            } else {
                echo "<p>Bilde nav pieejama</p>";
            }
            echo "<div class='productTxt'>";
            echo "<h3><a href='product_detail.php?productId=" . $row['productId'] . "'>" . $row['name'] . "</a></h3>";
            echo "<p>" . $row['size'] . "</p>";
            echo "<p>Cena: €" . number_format($row['price'], 2, '.', '') . "</p>";
            echo "</div>";
            echo "</div>";
        }
        ?>

    </div>

    <script>
        function toggleSortCriteria() {
            var x = document.getElementById("sortCriteria");
            if (x.style.display === "none") {
                x.style.display = "block";
                window.scrollTo(0, 0);
            } else {
                x.style.display = "none";
            }
        }

        document.getElementById('sortOrder').addEventListener('change', function() {
            this.form.submit(); // Submit form on sort order change
        });
    </script>
</body>
</html>
