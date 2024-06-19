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
        $stmt->close();

        // Generate additional booked times based on the 90-minute rule
        $extendedBookedTimes = [];
        foreach ($bookedTimes as $time) {
            $extendedBookedTimes[] = $time;
            $timeObject = DateTime::createFromFormat('H:i', $time);
            for ($i = 1; $i <= 3; $i++) {
                $timeObject->modify('+30 minutes');
                $extendedBookedTimes[] = $timeObject->format('H:i');
            }
            $timeObject = DateTime::createFromFormat('H:i', $time);
            for ($i = 1; $i <= 3; $i++) {
                $timeObject->modify('-30 minutes');
                $extendedBookedTimes[] = $timeObject->format('H:i');
            }
        }

        echo json_encode(array_unique($extendedBookedTimes));
    } else {
        echo json_encode([]);
    }
    $conn->close();
} else {
    echo json_encode([]);
}
