<?php
include "adminheader.php";
include '../includes/dbh.inc.php';

$conn = mysqli_connect('localhost', 'root', '', 'soins') or die('connection failed');

if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}

$query = "
    SELECT consultations.cons_date, consultations.cons_time, consultations.type, users.name as username, masters.mFullName
    FROM consultations
    JOIN users ON consultations.usersId = users.usersId
    JOIN masters ON consultations.masterId = masters.masterId
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Consultations</title>
    <link rel="stylesheet" href="css/consultations.css">
</head>
<body>
    <div class="container">
        <h2>All Consultations</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Type</th>
                    <th>User</th>
                    <th>Master</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['cons_date']) . "</td>
                                <td>" . htmlspecialchars($row['cons_time']) . "</td>
                                <td>" . htmlspecialchars($row['type']) . "</td>
                                <td>" . htmlspecialchars($row['username']) . "</td>
                                <td>" . htmlspecialchars($row['mFullName']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No consultations found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>