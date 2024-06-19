<?php
include "adminheader.php";
include '../includes/dbh.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['accept'])) {
        // Apstrada "Apstiprināt"
        $cons_date = $_POST['cons_date'];
        $cons_time = $_POST['cons_time'];
        $type = $_POST['type'];
        $username = $_POST['username'];
        $mFullName = $_POST['mFullName'];
        
        //"Apstiprināts"
        $updateQuery = "UPDATE consultations 
                        SET status = 'Apstiprināts' 
                        WHERE cons_date = '$cons_date' 
                        AND cons_time = '$cons_time' 
                        AND type = '$type' 
                        AND usersId IN (SELECT usersId FROM users WHERE name = '$username') 
                        AND masterId IN (SELECT masterId FROM masters WHERE mFullName = '$mFullName')";

        if ($conn->query($updateQuery) === TRUE) {
            echo "<div style='text-align: center; padding: 10px; margin: 10px auto; width: 50%; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;'>Konsultācija ir apstiprināta.</div>";
        } else {
            echo "<div style='text-align: center; padding: 10px; margin: 10px auto; width: 50%; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;'>Kļūda, apstiprinot konsultāciju: " . $conn->error . "</div>";
        }
    } elseif(isset($_POST['reject'])) {
        // Apstrada "Atcelt"
        $cons_date = $_POST['cons_date'];
        $cons_time = $_POST['cons_time'];
        $type = $_POST['type'];
        $username = $_POST['username'];
        $mFullName = $_POST['mFullName'];
        
        //"Atcelts"
        $updateQuery = "UPDATE consultations 
                        SET status = 'Atcelts' 
                        WHERE cons_date = '$cons_date' 
                        AND cons_time = '$cons_time' 
                        AND type = '$type' 
                        AND usersId IN (SELECT usersId FROM users WHERE name = '$username') 
                        AND masterId IN (SELECT masterId FROM masters WHERE mFullName = '$mFullName')";

        if ($conn->query($updateQuery) === TRUE) {
            echo "<div style='text-align: center; padding: 10px; margin: 10px auto; width: 50%; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;'>Konsultācija ir atcelta.</div>";
        } else {
            echo "<div style='text-align: center; padding: 10px; margin: 10px auto; width: 50%; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;'>Kļūda, atceļojot konsultāciju: " . $conn->error . "</div>";
        }
    }
}
