<?php
require 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quest_id'])) {
    $quest_id = intval($_POST['quest_id']); // Sanitize input

    // Fetch the quest details from the database regardless of status
    $sql = "SELECT * FROM quests WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $quest_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $quest = $result->fetch_assoc();

        // Reset the quest status to 'active' and store quest details in the session
        $update_sql = "UPDATE quests SET status = 'active' WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $quest_id);
        $update_stmt->execute();

        // Store quest details in the session
        $_SESSION['qid'] = $quest['id'];
        $_SESSION['quest_name'] = $quest['name'];
        $_SESSION['target_count'] = $quest['target_count'];
        $_SESSION['current_count'] = 0; // Reset progress to 0
        $_SESSION['reward'] = $quest['reward'];

        // Redirect back to the quest page
        header("Location: quests.php");
        exit();
    } else {
        // Quest not found
        echo "<p>Quest not found. Please try again.</p>";
    }

    $stmt->close();
} else {
    // Invalid request
    echo "<p>Invalid request. Please go back and try again.</p>";
}

$conn->close();
?>
