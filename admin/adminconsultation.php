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
    <title>Visi Konsultācijas</title>
    <link rel="stylesheet" href="css/consultations.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            margin: 2px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .accept-btn {
            background-color: #A46186;
            color: white;
        }

        .accept-btn:hover {
            background-color: #A07B9E;
        }

        .reject-btn {
            background-color: #C2A5B4; 
            color: white;
        }

        .reject-btn:hover {
            background-color: #B08FA3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Konsultācijas</h2>
        <table>
            <thead>
                <tr>
                    <th>Datums</th>
                    <th>Laiks</th>
                    <th>Veids</th>
                    <th>Lietotājs</th>
                    <th>Meistars</th>
                    <th>Darbība</th>
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
                                <td>
                                    <form action='process_consultation.php' method='post'>
                                        <input type='hidden' name='cons_date' value='" . $row['cons_date'] . "'>
                                        <input type='hidden' name='cons_time' value='" . $row['cons_time'] . "'>
                                        <input type='hidden' name='type' value='" . $row['type'] . "'>
                                        <input type='hidden' name='username' value='" . $row['username'] . "'>
                                        <input type='hidden' name='mFullName' value='" . $row['mFullName'] . "'>
                                        <button type='submit' class='btn accept-btn' name='accept'>Apstiprināt</button>
                                        <button type='submit' class='btn reject-btn' name='reject'>Atcelt</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Nav atrastas konsultācijas</td></tr>";
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
