<?php
require 'config.php'; // Include the configuration for database
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash password securely

    // Check if the username already exists
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Username already taken. Please choose another.";
        exit();
    }

    // Insert new user into the database
    $stmt->close();
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $stmt->insert_id; // Store user ID
        header("Location: character_creation.php");
        exit();
    } else {
        echo "Registration failed. Please try again.";
    }

    $stmt->close();
}
$conn->close();
?>
