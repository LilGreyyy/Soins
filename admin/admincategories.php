<?php
include "adminheader.php";
include '../includes/dbh.inc.php';

$errorMessage = "";
$successMessageAdd = "";
$successMessageDelete = "";

// Iegūstam kategoriju sarakstu no datu bāzes
$categoryListQuery = "SELECT categoryId, categoryName FROM categories";
$categoryListResult = $conn->query($categoryListQuery);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Jaunās kategorijas pievienošanas apstrāde
    if(isset($_POST['addCategory'])) {
        $newCategoryName = $_POST['newCategory'];
        $addCategoryQuery = "INSERT INTO categories (categoryName) VALUES ('$newCategoryName')";
        
        if ($conn->query($addCategoryQuery) === TRUE) {
            $successMessageAdd = "Jauna kategorija veiksmīgi pievienota";
        } else {
            $errorMessage = "Kļūda, pievienojot jaunu kategoriju: " . $addCategoryQuery . "<br>" . $conn->error;
        }
    }

    // Dzēšam kategoriju
    if(isset($_POST['deleteCategory'])) {
        $categoryId = $_POST['categoryId'];
        // Проверяем, связана ли категория с каким-либо мастером
        $checkMasterQuery = "SELECT * FROM masters WHERE categoryId=$categoryId";
        $checkMasterResult = $conn->query($checkMasterQuery);
        
        if ($checkMasterResult->num_rows > 0) {
            $errorMessage = "Невозможно удалить категорию, так как она связана с мастером.";
        } else {
            $confirmDelete = isset($_POST['confirmDelete']) ? $_POST['confirmDelete'] : '';
            if ($confirmDelete === 'yes') {
                $deleteCategoryQuery = "DELETE FROM categories WHERE categoryId=$categoryId";
                
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
                } else {
                    $errorMessage = "Kļūda, dzēšot kategoriju: " . $deleteCategoryQuery . "<br>" . $conn->error;
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
    <link rel="stylesheet" href="css/adminmasters.css">
</head>
<body>
    <div class="container">
        <!-- Forma, lai pievienotu jaunu kategoriju -->
        <div class="mccontainer">
            <h2>Pievienot kategoriju</h2>
            <?php if (!empty($errorMessage) && empty($successMessageAdd)) : ?>
                <div class="error"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
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
            <?php if (!empty($successMessageDelete)) : ?>
                <div class="success"><?php echo $successMessageDelete; ?></div>
            <?php endif; ?>
            <ul>
                <?php 
                while($row = $categoryListResult->fetch_assoc()) {
                    echo "<li>" . $row["categoryName"] . " <form action='admincategories.php' method='post' onsubmit='return confirmDelete()'><input type='hidden' name='categoryId' value='" . $row["categoryId"] . "'><input type='hidden' name='deleteCategory' value='true'><button type='submit'>Dzēst</button></form>";
                    if (!empty($successMessageDelete) && isset($_POST['deleteCategory']) && $_POST['categoryId'] == $row["categoryId"]) {
                        echo "<span class='success'>" . $successMessageDelete . "</span>";
                    }
                    echo "</li>";
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
