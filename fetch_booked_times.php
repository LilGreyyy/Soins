<?php
include 'includes/dbh.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'], $_POST['masterId'])) {
    $date = $_POST['date'];
    $masterId = $_POST['masterId'];

    $stmt = $conn->prepare("SELECT cons_time FROM consultations WHERE cons_date = ? AND masterId = ?");
    if ($stmt) {
        $stmt->bind_param("si", $date, $masterId);
        $stmt->execute();
        $stmt->bind_result($cons_time);
        $bookedTimes = [];
        while ($stmt->fetch()) {
            $bookedTimes[] = $cons_time;
        }
        echo json_encode($bookedTimes);
        $stmt->close();
    } else {
        echo json_encode([]);
    }
    $conn->close();
} else {
    echo json_encode([]);
}
