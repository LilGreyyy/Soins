<?php
include "adminheader.php";
include '../includes/dbh.inc.php';

if (isset($_GET['delete_user'])) {
    $delete_user_id = $_GET['delete_user'];
    $sql = "DELETE FROM users WHERE usersId = $delete_user_id";
    mysqli_query($conn, $sql);
    header("Location: user_management.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lietotāju pārvaldība</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .user-image {
            max-width: 100px;
            max-height: 100px;
            width: auto;
            height: auto;
        }

        .delete-button {
            background-color: #d46a6a;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .delete-button:hover {
            background-color: #c94c4c; 
        }
    </style>
</head>
<body>
    <h2>Lietotāju pārvaldība</h2>
    <table>
        <tr>
            <th>Lietotāja ID</th>
            <th>Vārds</th>
            <th>E-pasts</th>
            <th>Parole</th>
            <th>Numeris</th>
            <th>Darbība</th> 
        </tr>
        <?php
        $sql = "SELECT * FROM users WHERE user_type = 0";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>".$row['usersId']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['password']."</td>";
                echo "<td>".$row['number']."</td>";
                echo "<td><a class='delete-button' href='?delete_user=".$row['usersId']."'>Dzēst</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Nav atrasti lietotāji</td></tr>";
        }
        ?>
    </table>
</body>
</html>
