<?php
// Include necessary files and start the session
require 'db_connection.php';
session_start();

// Simulate a monster being defeated
$monster_defeated = true; // This would be determined by your battle mechanics logic

if ($monster_defeated) {
    echo "<p>You defeated a monster!</p>";

    // Check if a quest is active
    if (isset($_SESSION['qid']) && isset($_SESSION['target_count'])) {
        // Increment quest progress
        $_SESSION['current_count']++;

        // Check if the quest is completed
        if ($_SESSION['current_count'] >= $_SESSION['target_count']) {
            // Mark the quest as completed in the database
            $sql = "UPDATE quests SET status = 'completed' WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $_SESSION['qid']);
            $stmt->execute();

            // Notify the player of quest completion
            echo "<p>Congratulations! You completed the quest: " . htmlspecialchars($_SESSION['quest_name']) . ".</p>";
            echo "<p>Reward earned: " . $_SESSION['reward'] . " gold!</p>";

            // Clear quest-related session data
            unset($_SESSION['qid'], $_SESSION['quest_name'], $_SESSION['target_count'], $_SESSION['current_count'], $_SESSION['reward']);
        } else {
            // Notify the player of progress
            echo "<p>Quest progress: " . $_SESSION['current_count'] . "/" . $_SESSION['target_count'] . " monsters defeated.</p>";
        }
    } else {
        echo "<p>No active quest. Keep fighting monsters to improve your skills!</p>";
    }
} else {
    echo "<p>You didn't defeat the monster. Better luck next time!</p>";
}
?>
