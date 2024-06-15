<?php
include_once 'blocks/header.php';
include 'includes/dbh.inc.php';

$error = '';
$successMessage = '';

if (!isset($_SESSION['user_id'])) {
    echo "Session is not set.<br>";
    header("Location: login.php");
    exit();
}

// Fetch categories from the database
$categories = [];
$result = $conn->query("SELECT categoryId, categoryName FROM categories");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
} else {
    echo "No categories found.<br>";
}

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $userName = '';

    $stmt = $conn->prepare("SELECT name FROM users WHERE usersId = ?");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($userName);
        $stmt->fetch();
        $stmt->close();
    } else {
        echo "Failed to prepare statement.";
    }
} else {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cons_date'], $_POST['cons_time'], $_POST['type'], $_POST['masterName'])) {
        $cons_date = $_POST['cons_date'];
        $cons_time = $_POST['cons_time'];
        $type = $_POST['type'];
        $masterName = $_POST['masterName'];

        $stmt = $conn->prepare("SELECT masterId FROM masters WHERE mFullName = ?");
        if ($stmt) {
            $stmt->bind_param("s", $masterName);
            $stmt->execute();
            $stmt->bind_result($masterId);
            $stmt->fetch();
            $stmt->close();
        } else {
            $error = "Failed to prepare statement.";
        }

        if ($masterId) {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM consultations WHERE cons_date = ? AND cons_time = ? AND masterId = ?");
            if ($stmt) {
                $stmt->bind_param("ssi", $cons_date, $cons_time, $masterId);
                $stmt->execute();
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();
            } else {
                $error = "Failed to prepare statement.";
            }

            if ($count == 0) {
                $stmt = $conn->prepare("INSERT INTO consultations (cons_date, cons_time, type, masterId, userName, usersId) VALUES (?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("sssisi", $cons_date, $cons_time, $type, $masterId, $userName, $userId);

                    if ($stmt->execute()) {
                        $successMessage = "Consultation added successfully";
                    } else {
                        $error = "Error: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    $error = "Failed to prepare statement.";
                }
            } else {
                $error = "The selected date and time is already booked.";
            }
        } else {
            $error = "Invalid master selected.";
        }
    } else {
        $error = "All fields are required.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/consultations.css">
    <title>Pievienot konsultāciju</title>
    <script>
        function disablePastDatesAndTimes() {
            var today = new Date().toISOString().split('T')[0];
            document.getElementById("cons_date").setAttribute("min", today);
        }

        function generateTimeOptions() {
            var timeSelect = document.getElementById("cons_time");
            var startTime = 9; // 9:00
            var endTime = 19; // 19:00

            for (var hour = startTime; hour <= endTime; hour++) {
                ["00", "30"].forEach(function (minutes) {
                    var timeValue = (hour < 10 ? "0" : "") + hour + ":" + minutes;
                    var option = document.createElement("option");
                    option.value = timeValue;
                    option.text = timeValue;
                    timeSelect.appendChild(option);
                });
            }
        }

        function fetchMasters(categoryId) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "fetch_masters.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("masterName").innerHTML = xhr.responseText;
                    fetchBookedTimes();
                }
            };
            xhr.send("categoryId=" + categoryId);
        }

        function fetchBookedTimes() {
            var date = document.getElementById("cons_date").value;
            var masterId = document.getElementById("masterName").value;

            if (!date || !masterId) return;

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "fetch_booked_times.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var bookedTimes = JSON.parse(xhr.responseText);
                    var timeSelect = document.getElementById("cons_time");
                    var options = timeSelect.getElementsByTagName("option");
                    for (var i = 0; i < options.length; i++) {
                        options[i].disabled = bookedTimes.includes(options[i].value);
                    }
                }
            };
            xhr.send("date=" + date + "&masterId=" + masterId);
        }

        window.onload = function() {
            disablePastDatesAndTimes();
            generateTimeOptions();
            setInterval(fetchBookedTimes, 5400000); // обновление каждые 90 минут (5400000 миллисекунд)
        }
    </script>
</head>
<body>
    <div class="intro-text">
        <h2>Laipni lūdzam mūsu konsultāciju sadaļā!</h2>
        <p>
            Mēs piedāvājam plašu pakalpojumu klāstu, kas vērsti uz jūsu personīgo aprūpi un labklājību. 
            Mūsu meistari ir ļoti atbildīgi un profesionāli, nodrošinot visaugstāko apkalpošanas kvalitāti. 
            Jūs varat pierakstīties uz konsultāciju, izvēloties sev ērtāko datumu un laiku, kā arī piemērotāko meistaru. 
            Neatkarīgi no tā, vai vēlaties klātienes tikšanos vai attālinātu konsultāciju, mēs esam šeit, lai jums palīdzētu!
        </p>
    </div>

    <form action="consultation.php" method="post">
        <h2>Pieraksts uz konsultāciju</h2>
        <label for="cons_date">Konsultācijas datums</label>
        <input type="date" id="cons_date" name="cons_date" onchange="fetchBookedTimes()" required><br><br>
        
        <label for="cons_time">Konsultācijas laiks</label>
        <select id="cons_time" name="cons_time" required>
            <option value="">Laiks</option>
        </select><br><br>
        
        <label for="type">Konsultācijas veids</label>
        <select id="type" name="type" required>
            <option value="">Veids</option>
            <option value="Attalināti">Attalināti</option>
            <option value="Klātienē">Klātienē</option>
        </select><br><br>
        
        <label for="category">Kategorija:</label>
        <select id="category" name="category" onchange="fetchMasters(this.value)" required>
            <option value="">Kategorija</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['categoryId']); ?>">
                    <?php echo htmlspecialchars($category['categoryName']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        
        <label for="masterName">Meistars:</label>
        <select id="masterName" name="masterName" onchange="fetchBookedTimes()" required>
            <option value="">Meistars</option>
        </select><br><br>
        
        <?php if ($error): ?>
            <p style="color: grey;"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <?php if ($successMessage): ?>
            <p style="color: green;"><?php echo $successMessage; ?></p>
        <?php endif; ?>
        
        <input type="submit" value="Akceptēt pierakstu">
    </form>
</body>
</html>
