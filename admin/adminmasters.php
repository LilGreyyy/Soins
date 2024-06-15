<?php
include "adminheader.php";
include '../includes/dbh.inc.php';

$successMessage = $errorMessage = "";

// Iegūstam kategoriju sarakstu no datu bāzes
$categoryListQuery = "SELECT * FROM categories";
$categoryListResult = $conn->query($categoryListQuery);

// Iegūstam meistaru sarakstu no datu bāzes
$masterListQuery = "SELECT masters.masterId, masters.mFullName, categories.categoryName, masters.workTimeOpen, masters.workTimeClose 
                    FROM masters 
                    INNER JOIN categories_masters ON masters.masterId = categories_masters.masterId
                    INNER JOIN categories ON categories_masters.categoryId = categories.categoryId";
$masterListResult = $conn->query($masterListQuery);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_delete'])) {
    $masterId = $_POST['master_id'];
    $confirmDelete = $_POST['confirm_delete'];

    if ($confirmDelete == 'yes') {
        // Dzēšanas vaicājums kategorijām un meistariem
        $deleteCategoriesMastersQuery = "DELETE FROM categories_masters WHERE masterId = ?";
        $stmt = $conn->prepare($deleteCategoriesMastersQuery);
        $stmt->bind_param("i", $masterId);
        $stmt->execute();

        // Dzēšanas vaicājums no tabulas "masters"
        $deleteMasterQuery = "DELETE FROM masters WHERE masterId = ?";
        $stmt = $conn->prepare($deleteMasterQuery);
        $stmt->bind_param("i", $masterId);
        if ($stmt->execute()) {
            $successMessage = "Meistars veiksmīgi dzēsts.";
        } else {
            $errorMessage = "Dzēšanas kļūda: " . $conn->error;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['confirm_delete'])) {
    // Jaunās meistara pievienošanas apstrāde
    $mFullName = $_POST['mFullName'];
    $categoryName = $_POST['category']; // Pārņemam izvēlēto kategoriju
    $workTimeOpen = $_POST['workTimeOpen'];
    $workTimeClose = $_POST['workTimeClose'];
    $mEmail = $_POST['mEmail'];
    $mPassword = trim($_POST['mPassword']);

    // Paroles hash iegūšana
    $hashed_password = password_hash($mPassword, PASSWORD_DEFAULT);
        
    // Attēla augšupielādes apstrāde
    $targetDir = dirname(__FILE__) . "/masteruploads/"; // Pielāgots mērķa katalogs
    $targetFile = $targetDir . basename($_FILES["photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
    // Pārbauda, vai augšupielādētais fails ir attēls
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $errorMessage = "Fails nav attēls.";
        $uploadOk = 0;
    }
        
    // Pārbauda, vai fails jau eksistē
    if (file_exists($targetFile)) {
        $errorMessage = "Atvainojiet, fails jau eksistē.";
        $uploadOk = 0;
    }
        
    // Pārbauda attēla izmēru
    if ($_FILES["photo"]["size"] > 500000) {
        $errorMessage = "Atvainojiet, jūsu fails ir pārāk liels.";
        $uploadOk = 0;
    }
        
    // Atļautie attēla formāti
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" && $imageFileType != "jfif") {
        $errorMessage = "Atvainojiet, atļauti tikai JPG, JPEG, PNG, GIF un JFIF faili.";
        $uploadOk = 0;
    }
        
    // Pārbauda, vai $uploadOk nav iestatīts uz 0 kā kļūda
    if ($uploadOk == 0) {
        $errorMessage = "Atvainojiet, jūsu fails netika augšupielādēts.";
    } else {
        // ja viss kārtībā, mēģiniet augšupielādēt failu
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
            // Fails veiksmīgi augšupielādēts, tagad iegūstam kategorijas ID no tabulas 'categories'
                
            // Pārbauda, vai kategorija pastāv
            $catQuery = "SELECT categoryId FROM categories WHERE categoryName = ?";
            $stmt = $conn->prepare($catQuery);
            $stmt->bind_param("s", $categoryName);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $categoryId = $row["categoryId"];
                
                // Kategorijas ID ir iegūts, tagad ievietojam datus tabulā 'masters'
                $query = "INSERT INTO masters (mFullName, mPhoto, workTimeOpen, workTimeClose, mEmail, mPassword, type) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $type = 2; // Здесь вносим значение 2 для поля type
                $stmt->bind_param("ssssssi", $mFullName, $targetFile, $workTimeOpen, $workTimeClose, $mEmail, $hashed_password, $type);
                
                if ($stmt->execute()) {
                    $masterId = $conn->insert_id;
                    // Tagad pievienojam meistara ID tabulā 'categories_masters'
                    $insertQuery = "INSERT INTO categories_masters (categoryId, masterId) VALUES (?, ?)";
                    $stmt = $conn->prepare($insertQuery);
                    $stmt->bind_param("ii", $categoryId, $masterId);
                    if ($stmt->execute()) {
                        $successMessage = "Jaunais meistars veiksmīgi pievienots";
                    } else {
                        $errorMessage = "Kļūda: " . $insertQuery . "<br>" . $conn->error;
                    }
                } else {
                    $errorMessage = "Kļūda: " . $query . "<br>" . $conn->error;
                }
            } else {
                $errorMessage = "Izvēlētā kategorija nav atrasta datu bāzē.";
            }

            $stmt->close();
        } else {
            $errorMessage = "Atvainojiet, radās kļūda, augšupielādējot jūsu failu.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pievienot meistaru</title>
    <link rel="stylesheet" href="css/adminmasters.css">
    <script>
        function confirmDelete() {
            return confirm("Vai tiešām vēlaties dzēst meistaru?");
        }
    </script>
</head>
<body>
    <div class="container">
        <!-- Forma, lai pievienotu jaunu meistaru -->
        <div class="mmcontainer">
            <h2>Pievienot jaunu meistaru</h2>
            <?php if (!empty($errorMessage)) : ?>
                <div class="error"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
            <?php if (!empty($successMessage)) : ?>
                <div class="success"><?php echo $successMessage; ?></div>
            <?php endif; ?>
            <form action="adminmasters.php" method="post" enctype="multipart/form-data">
                <label for="mFullName">Pilnais vārds:</label>
                <input type="text" id="mFullName" name="mFullName" required><br><br>
                
                <label for="photo">Bilde:</label>
                <input type="file" id="photo" name="photo" accept="image/*" required><br><br>
                
                <label for="category">Kategorija:</label>
                <select id="category" name="category" required>
                    <option value="" selected disabled>Izvēlieties kategoriju</option>
                    <?php
                    if ($categoryListResult->num_rows > 0) {
                        while($row = $categoryListResult->fetch_assoc()) {
                            echo "<option value='".$row["categoryName"]."'>".$row["categoryName"]."</option>";
                        }
                    }
                    ?>
                </select><br><br>
                
                <label for="workTimeOpen">Darba sākuma laiks:</label>
                <input type="time" id="workTimeOpen" name="workTimeOpen" required><br><br>

                <label for="workTimeClose">Darba beigu laiks:</label>
                <input type="time" id="workTimeClose" name="workTimeClose" required><br><br>

                <label for="mEmail">E-pasta adrese:</label>
                <input type="email" id="mEmail" name="mEmail" required><br><br>

                <label for="mPassword">Parole:</label>
                <input type="password" id="mPassword" name="mPassword" required><br><br>
                
                <button type="submit">Pievienot meistaru</button>
            </form>
        </div>
        <!-- Tabula ar esošajiem meistariem -->
        <div class="mmcontainer">
            <h2>Esošie meistari</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Pilnais vārds</th>
                    <th>Kategorija</th>
                    <th>Darba laiks</th>
                    <th>Darbība</th>
                </tr>
                <?php
                if ($masterListResult->num_rows > 0) {
                    while($row = $masterListResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row["masterId"]."</td>";
                        echo "<td>".$row["mFullName"]."</td>";
                        echo "<td>".$row["categoryName"]."</td>";
                        echo "<td>".$row["workTimeOpen"]. " - " . $row["workTimeClose"]."</td>";
                        echo "<td>
                            <form action='adminmasters.php' method='post' onsubmit='return confirmDelete();'>
                                <input type='hidden' name='master_id' value='".$row["masterId"]."'>
                                <input type='hidden' name='confirm_delete' value='yes'>
                                <button type='submit'>Dzēst</button>
                            </form>
                        </td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
