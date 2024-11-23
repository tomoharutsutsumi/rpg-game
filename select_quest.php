<?php
// Include the configuration file for database constants
require 'config.php';

// Establish a database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check the database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

session_start();

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the quest ID from the form submission
    $quest_id = intval($_POST['quest_id']);

    // Fetch the selected quest's details from the database
    $sql = "SELECT * FROM quests WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $quest_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the quest exists, proceed
    if ($result->num_rows === 1) {
        $quest = $result->fetch_assoc();

        // Store the quest details in the session
        $_SESSION['qid'] = $quest['id'];
        $_SESSION['quest_name'] = $quest['name'];
        $_SESSION['target_count'] = $quest['target_count'];
        $_SESSION['current_count'] = 0; // Progress starts at 0
        $_SESSION['reward'] = $quest['reward'];

        // Update the quest status to 'active' in the database
        $update_sql = "UPDATE quests SET status = 'active' WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $quest_id);
        $update_stmt->execute();

        // Output confirmation to the user
        echo "<p>Quest '" . htmlspecialchars($quest['name']) . "' accepted! Your goal is to defeat " . htmlspecialchars($quest['target_count']) . " monsters.</p>";
    } else {
        // Quest not found
        echo "<p>Quest not found. Please try again.</p>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Invalid request
    echo "<p>Invalid request. Please go back and try again.</p>";
}
?>
