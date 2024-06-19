_masters.php
<?php
include_once 'includes/dbh.inc.php';

if (isset($_POST['categoryId'])) {
    $categoryId = $_POST['categoryId'];

    $stmt = $conn->prepare("SELECT masters.masterId, masters.mFullName FROM masters INNER JOIN categories_masters ON masters.masterId = categories_masters.masterId WHERE categories_masters.categoryId = ?");
    
    if ($stmt) {
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        $masters = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        foreach ($masters as $master) {
            echo "<option value='" . $master['masterId'] . "'>" . htmlspecialchars($master['mFullName']) . "</option>";
        }
    } else {
        echo "Failed to prepare statement.";
    }
}

$conn->close();
