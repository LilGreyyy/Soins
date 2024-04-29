<!DOCTYPE html>
<html>
<head>
    <title>Consultation Review Page</title>
</head>
<body>
    <?php
    include 'db_connection.php'; // Include the database connection script

    // Get consultation ID from the URL parameter
    $consId = $_GET['consId'];

    // Fetch consultation details
    $consultation_sql = "SELECT * FROM consultations WHERE cons_id = $consId";
    $consultation_result = $conn->query($consultation_sql);
    $consultation_row = $consultation_result->fetch_assoc();

    // Display consultation details
    echo "<h2>Consultation Details</h2>";
    echo "<p>Date: {$consultation_row['cons_date']}</p>";
    echo "<p>Time: {$consultation_row['cons_time']}</p>";
    echo "<p>Type: {$consultation_row['type']}</p>";

    // Fetch user details
    $userId = $consultation_row['usersId'];
    $user_sql = "SELECT * FROM users WHERE usersId = $userId";
    $user_result = $conn->query($user_sql);
    $user_row = $user_result->fetch_assoc();

    echo "<h3>User Details</h3>";
    echo "<p>Name: {$user_row['name']}</p>";
    echo "<p>Email: {$user_row['email']}</p>";

    // Fetch master details
    $masterId = $consultation_row['masterId'];
    $master_sql = "SELECT * FROM masters WHERE masterId = $masterId";
    $master_result = $conn->query($master_sql);
    $master_row = $master_result->fetch_assoc();

    echo "<h3>Master Details</h3>";
    echo "<p>Name: {$master_row['mFullName']}</p>";
    echo "<p>Category: {$master_row['mCategory']}</p>";

    // Fetch reviews for the consultation
    $review_sql = "SELECT * FROM consultation_review WHERE cons_id = $consId";
    $review_result = $conn->query($review_sql);

    if ($review_result->num_rows > 0) {
        // Display reviews
        echo "<h3>Consultation Reviews</h3>";
        while($review_row = $review_result->fetch_assoc()) {
            echo "<p>User: {$review_row['usersId']}</p>";
            echo "<p>Rating: {$review_row['rating']}</p>";
            echo "<p>Review Date: {$review_row['review_date']}</p>";
            echo "<p>Comment: {$review_row['comment']}</p><hr>";
        }
    } else {
        echo "<p>No reviews yet for this consultation.</p>";
    }

    $conn->close();
    ?>
</body>
</html>