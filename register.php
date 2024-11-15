<?php
// register.php

// Include the database connection file
require_once 'db_connection.php';

// Process the form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user inputs
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate inputs
    if (empty($username) || empty($password)) {
        echo "Please fill in all fields.";
        exit();
    }

    // Check if the username already exists
    $sql = "SELECT id FROM users WHERE username = :username";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);

        if ($stmt->fetch()) {
            echo "This username is already taken.";
            exit();
        }
    } catch (Exception $e) {
        echo ("Error checking username existence: " . $e->getMessage());
        echo "An error occurred. Please try again later.";
        exit();
    }

    // Insert the new user into the database
    $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
    try {
        $stmt = $pdo->prepare($sql);
        // Hash the password securely
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt->execute([
            'username' => $username,
            'password' => $hashed_password,
        ]);

        echo "Registration successful!";
    } catch (Exception $e) {
        echo ("Error inserting new user: " . $e->getMessage());
        echo "An error occurred. Please try again later.";
        exit();
    }
}
?>
