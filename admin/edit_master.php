<?php
include "adminheader.php";
include '../includes/dbh.inc.php';

$successMessage = $errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Jaunas meistara informācijas saglabāšanas apstrāde
    if (isset($_POST['save_master'])) {
        $masterId = $_POST['master_id'];
        $mFullName = $_POST['mFullName'];
        $workTimeOpen = $_POST['workTimeOpen'];
        $workTimeClose = $_POST['workTimeClose'];
        $mEmail = $_POST['mEmail'];
        $mPassword = trim($_POST['mPassword']);

        // Paroles hash iegūšana
        $hashed_password = password_hash($mPassword, PASSWORD_DEFAULT);

        // Attēla augšupielādes apstrāde (ja nepieciešams)
        if ($_FILES["photo"]["size"] > 0) {
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
                    // Attēls veiksmīgi augšupielādēts
                    $mPhoto = $targetFile;
                } else {
                    $errorMessage = "Atvainojiet, radās kļūda, augšupielādējot jūsu failu.";
                }
            }
        }

        // Pārbauda, vai $mPhoto definēts, ja nē, tad nevajag mainīt bildi, citādi - atjaunot arī bildi
        if (!isset($mPhoto)) {
            $updateMasterQuery = "UPDATE masters SET mFullName=?, workTimeOpen=?, workTimeClose=?, mEmail=?, mPassword=? WHERE masterId=?";
            $stmt = $conn->prepare($updateMasterQuery);
            $stmt->bind_param("sssssi", $mFullName, $workTimeOpen, $workTimeClose, $mEmail, $hashed_password, $masterId);
        } else {
            $updateMasterQuery = "UPDATE masters SET mFullName=?, mPhoto=?, workTimeOpen=?, workTimeClose=?, mEmail=?, mPassword=? WHERE masterId=?";
            $stmt = $conn->prepare($updateMasterQuery);
            $stmt->bind_param("ssssssi", $mFullName, $mPhoto, $workTimeOpen, $workTimeClose, $mEmail, $hashed_password, $masterId);
        }

        if ($stmt->execute()) {
            $successMessage = "Meistara informācija veiksmīgi atjaunota.";
        } else {
            $errorMessage = "Kļūda, atjauninot meistara informāciju: " . $stmt->error;
        }
    }

    // Ja tika nospiesta atcelšanas poga, pārvirza uz adminmasters.php lapu
    if (isset($_POST['cancel'])) {
        header("Location: adminmasters.php");
        exit();
    }
}

// Iegūstam meistara informāciju no datu bāzes
if (isset($_POST['master_id'])) {
    $masterId = $_POST['master_id'];
    $getMasterQuery = "SELECT * FROM masters WHERE masterId=?";
    $stmt = $conn->prepare($getMasterQuery);
    $stmt->bind_param("i", $masterId);
    $stmt->execute();
    $result = $stmt->get_result();
    $master = $result->fetch_assoc();
}

?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rediģēt meistaru</title>
    <link rel="stylesheet" href="css/adminmasters.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fcd3d7; 
        }

        .container {
            max-width: 900px; 
            margin: 20px auto;
            overflow: hidden;
            padding: 0 20px;
        }

        .mmcontainer {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .mmcontainer h2 {
            margin-bottom: 20px;
        }

        .mmcontainer label {
            font-weight: bold;
        }

        .mmcontainer input[type="text"],
        .mmcontainer input[type="email"],
        .mmcontainer input[type="password"],
        .mmcontainer input[type="time"],
        .mmcontainer select {
            width: calc(100% - 10px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .mmcontainer input[type="file"] {
            margin-bottom: 10px;
        }

        .mmcontainer button {
            background-color: #ffccd3; 
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .mmcontainer button[type="submit"]:hover,
        .mmcontainer button[type="button"]:hover {
            background-color: #ffa5ad;
        }

        .error {
            color: #f44336;
            margin-bottom: 10px;
        }

        .success {
            color: #4CAF50;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Forma, lai rediģētu meistara informāciju -->
    <div class="mmcontainer">
        <h2>Rediģēt meistaru</h2>
        <?php if (!empty($errorMessage)) : ?>
            <div class="error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <?php if (!empty($successMessage)) : ?>
            <div class="success"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        <form action="edit_master.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="master_id" value="<?php echo $master['masterId']; ?>">
            <label for="mFullName">Pilnais vārds:</label>
            <input type="text" id="mFullName" name="mFullName" value="<?php echo $master['mFullName']; ?>" required><br><br>

            <label for="photo">Bilde:</label>
            <input type="file" id="photo" name="photo" accept="image/*"><br><br>

            <label for="category">Kategorija:</label>
            <select id="category" name="category" disabled>
                <?php
                $getCategoryQuery = "SELECT categoryName FROM categories INNER JOIN categories_masters ON categories.categoryId = categories_masters.categoryId WHERE categories_masters.masterId=?";
                $stmt = $conn->prepare($getCategoryQuery);
                $stmt->bind_param("i", $masterId);
                $stmt->execute();
                $result = $stmt->get_result();
                $category = $result->fetch_assoc();
                ?>
                <option value="<?php echo $category['categoryName']; ?>" selected><?php echo $category['categoryName']; ?></option>
            </select><br><br>

            <label for="workTimeOpen">Darba sākuma laiks:</label>
            <input type="time" id="workTimeOpen" name="workTimeOpen" value="<?php echo $master['workTimeOpen']; ?>" required><br><br>

            <label for="workTimeClose">Darba beigu laiks:</label>
            <input type="time" id="workTimeClose" name="workTimeClose" value="<?php echo $master['workTimeClose']; ?>" required><br><br>

            <label for="mEmail">E-pasta adrese:</label>
            <input type="email" id="mEmail" name="mEmail" value="<?php echo $master['mEmail']; ?>" required><br><br>

            <label for="mPassword">Parole:</label>
            <input type="password" id="mPassword" name="mPassword" required><br><br>

            <button type="submit" name="save_master">Saglabāt izmaiņas</button>
            <button type="submit" name="cancel">Atcelt</button>
        </form>
    </div>
</div>
</body>
</html>
