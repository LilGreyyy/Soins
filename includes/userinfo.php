<?php
class UserInfo {
    private $conn;

    public function __construct($servername, $dbUserName, $dbPassword, $dbName) {
        // Create connection
        $this->conn = new mysqli($servername, $dbUserName, $dbPassword, $dbName);

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function __destruct() {
        // Close connection
        $this->conn->close();
    }

    public function getUserData($user_id) {
        // Retrieve user data
        $sql = "SELECT * FROM users WHERE usersId = $user_id";
        $result = $this->conn->query($sql);

        // Check for errors
        if (!$result) {
            die('Error retrieving user data: ' . $this->conn->error);
        }

        // Get user data
        $user_data = $result->fetch_assoc();

        return $user_data;
    }

    public function updateUserData($user_id, $name, $email, $username) {
        // Update user data
        $sql = "UPDATE users SET usersName = '$name', usersEmail = '$email', usersUid = '$username' WHERE usersId = $user_id";
        $result = $this->conn->query($sql);

        // Check for errors
        if (!$result) {
            die('Error updating user data: ' . $this->conn->error);
        }
    }
}