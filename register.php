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

    // Check if the username already exists in UserCredential table
    $sql = "SELECT UserName FROM UserCredential WHERE UserName = :username";
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

    // Begin a transaction
    $pdo->beginTransaction();

    try {
        // Insert into UserCredential table
        $sqlCredential = "INSERT INTO UserCredential (UserName, Password) VALUES (:username, :password)";
        $stmtCredential = $pdo->prepare($sqlCredential);

        // Hash the password securely
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmtCredential->execute([
            'username' => $username,
            'password' => $hashed_password,
        ]);

        // Insert into UserDetail table
        $sqlDetail = "INSERT INTO UserDetail (UserName, DateCreated) VALUES (:username, :datecreated)";
        $stmtDetail = $pdo->prepare($sqlDetail);

        $stmtDetail->execute([
            'username' => $username,
            'datecreated' => date('Y-m-d'), // Current date
        ]);

        // Commit the transaction
        $pdo->commit();

        echo "Registration successful!";
    } catch (Exception $e) {
        // Roll back the transaction if something failed
        $pdo->rollBack();
        echo ("Error inserting new user: " . $e->getMessage());
        echo "An error occurred. Please try again later.";
        exit();
    }
}
?>
