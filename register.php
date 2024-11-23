<?php
require_once 'db_connection.php'; // Include the database connection
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash the password securely

    // Check if the username already exists
    $check_sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Username already taken. Please choose another.";
    } else {
        // Insert the new user into the database
        $stmt->close();
        $insert_sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $stmt->insert_id; // Store the user's ID in the session
            header("Location: character_creation.php");
            exit();
        } else {
            echo "Error: Could not register user. Please try again.";
        }
    }

    $stmt->close();
}

$conn->close();
?>
