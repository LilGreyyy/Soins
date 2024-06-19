<?php
include "adminheader.php";
include '../includes/dbh.inc.php';

$errorMessage = "";
$successMessageAdd = "";
$successMessageDelete = "";
$categoryDeleted = false; // Mainīgais, lai noteiktu, vai kāda kategorija ir dzēsta

// Iegūstam kategoriju sarakstu no datu bāzes
$categoryListQuery = "SELECT categoryId, categoryName FROM categories";
$categoryListResult = $conn->query($categoryListQuery);

if (!$categoryListResult) {
    die("Vaicājuma izpilde neizdevās: " . $conn->error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Jaunās kategorijas pievienošanas apstrāde
    if(isset($_POST['addCategory'])) {
        $newCategoryName = $conn->real_escape_string($_POST['newCategory']);
        $addCategoryQuery = "INSERT INTO categories (categoryName) VALUES ('$newCategoryName')";
        
        if ($conn->query($addCategoryQuery) === TRUE) {
            $successMessageAdd = "Jauna kategorija veiksmīgi pievienota";
        } else {
            $errorMessage = "Kļūda, pievienojot jaunu kategoriju: " . $conn->error;
        }
    }

    // Dzēšam kategoriju
    if(isset($_POST['deleteCategory'])) {
        $categoryId = $conn->real_escape_string($_POST['categoryId']);
        // Pārbaudām, vai kategorija nav saistīta ar kādu meistaru caur categories_masters tabulu
        $checkMasterQuery = "SELECT * FROM categories_masters WHERE categoryId='$categoryId'";
        $checkMasterResult = $conn->query($checkMasterQuery);
        
        if ($checkMasterResult && $checkMasterResult->num_rows > 0) {
            $errorMessage = "Nevar izdzēst kategoriju, jo tā ir saistīta ar meistaru.";
        } else {
            $confirmDelete = isset($_POST['confirmDelete']) ? $_POST['confirmDelete'] : '';
            if ($confirmDelete === 'yes') {
                $deleteCategoryQuery = "DELETE FROM categories WHERE categoryId='$categoryId'";
                
                if ($conn->query($deleteCategoryQuery) === TRUE) {
                    // Iegūstam maksimālo kategorijas ID
                    $maxIdQuery = "SELECT MAX(categoryId) AS maxId FROM categories";
                    $maxIdResult = $conn->query($maxIdQuery);
                    $maxIdRow = $maxIdResult->fetch_assoc();
                    $maxId = $maxIdRow['maxId'];

                    // Atiestatam ID vērtības
                    $resetIdsQuery = "ALTER TABLE categories AUTO_INCREMENT = 1";
                    $conn->query($resetIdsQuery);

                    $successMessageDelete = "Kategorija veiksmīgi dzēsta";
                    $categoryDeleted = true; // Uzstādām, ka kāda kategorija ir dzēsta
                } else {
                    $errorMessage = "Kļūda, dzēšot kategoriju: " . $conn->error;
                }
            } else {
                $errorMessage = "Dzēšanas darbība atcelta!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pievienot kategoriju</title>
    <link rel="stylesheet" href="css/admincategories.css">
</head>
<body>
    <div class="container">
        <!-- Forma, lai pievienotu jaunu kategoriju -->
        <div class="mccontainer">
            <h2>Pievienot kategoriju</h2>
            <?php if (!empty($successMessageAdd)) : ?>
                <div class="success"><?php echo $successMessageAdd; ?></div>
            <?php endif; ?>
            <form action="admincategories.php" method="post">
                <label for="newCategory">Jauna kategorija:</label>
                <input type="text" id="newCategory" name="newCategory" required><br><br>
                <input type="hidden" name="addCategory" value="true">
                <button type="submit">Pievienot kategoriju</button>
            </form>
        </div>

        <!-- Kategoriju saraksts -->
        <div class="mccontainer">
            <h2>Kategoriju saraksts</h2>
            <?php if (!empty($errorMessage) && empty($successMessageDelete)) : ?>
                <div class="error"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
            <ul>
                <?php 
                while($row = $categoryListResult->fetch_assoc()) {
                    echo "<li>" . $row["categoryName"];
                    if (!$categoryDeleted || ($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_POST['deleteCategory']))) { // Parādīt dzēšanas pogu, ja kategorija nav dzēsta
                        echo " <form action='admincategories.php' method='post' onsubmit='return confirmDelete()'><input type='hidden' name='categoryId' value='" . $row["categoryId"] . "'><input type='hidden' name='deleteCategory' value='true'><input type='hidden' name='confirmDelete' value='yes'><button type='submit'>Dzēst</button></form>";
                    }
                    echo "</li>";
                    if (!empty($successMessageDelete) && isset($_POST['deleteCategory']) && $_POST['categoryId'] == $row["categoryId"]) {
                        echo "<div class='success'>" . $successMessageDelete . "</div>"; // Parādīt ziņojumu par dzēšanu, ja tas attiecas uz šo kategoriju
                    }
                }
                ?>
            </ul>
        </div>
    </div>

    <script>
        function confirmDelete() {
            return confirm("Vai Jūs tiešām vēlaties nodzēst kategoriju?");
        }
    </script>
</body>
</html>
