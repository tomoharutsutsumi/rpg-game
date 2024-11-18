<?php
// login.php

session_start();

// Include the database connection file
require_once 'db_connection.php';

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user inputs
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate inputs
    if (empty($username) || empty($password)) {
        echo "Please fill in all fields.";
        exit();
    }

    // Prepare a select statement to retrieve the hashed password
    $sql = "SELECT Password FROM UserCredential WHERE UserName = :username";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);

        // Fetch the user's hashed password
        $row = $stmt->fetch();
        if ($row) {
            $hashed_password = $row['Password'];

            // Verify the entered password with the hashed password
            if (password_verify($password, $hashed_password)) {
                // Password is correct, start a new session
                session_regenerate_id(true); // Regenerate session ID for security
                $_SESSION["loggedin"] = true;
                $_SESSION["username"] = $username;

                // Redirect to the welcome page
                header("Location: welcome.php");
                exit();
            } else {
                // Display an error message for invalid password
                echo "Invalid username or password.";
            }
        } else {
            // Display an error message for invalid username
            echo "Invalid username or password.";
        }
    } catch (Exception $e) {
        error_log("Error during login: " . $e->getMessage());
        echo "An error occurred. Please try again later.";
        exit();
    }
}
?>
