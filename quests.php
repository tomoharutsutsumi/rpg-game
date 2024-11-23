<?php
// Include necessary files and start the session
require 'db_connection.php';
session_start();

// Fetch available, active, and completed quests
$sql_available = "SELECT * FROM quests WHERE status = 'available'";
$sql_active = "SELECT * FROM quests WHERE status = 'active'";
$sql_completed = "SELECT * FROM quests WHERE status = 'completed'";

$result_available = $conn->query($sql_available);
$result_active = $conn->query($sql_active);
$result_completed = $conn->query($sql_completed);

echo "<h1>Quests</h1>";

// Display available quests
if ($result_available->num_rows > 0) {
    echo "<h2>Available Quests</h2>";
    echo "<ul>";
    while ($quest = $result_available->fetch_assoc()) {
        echo "<li>";
        echo "<h3>" . htmlspecialchars($quest['name']) . "</h3>";
        echo "<p>" . htmlspecialchars($quest['description']) . "</p>";
        echo "<p>Target: Defeat " . htmlspecialchars($quest['target_count']) . " monsters</p>";
        echo "<p>Reward: " . htmlspecialchars($quest['reward']) . " gold</p>";
        echo "<form method='POST' action='select_quest.php'>";
        echo "<input type='hidden' name='quest_id' value='" . $quest['id'] . "'>";
        echo "<button type='submit'>Accept Quest</button>";
        echo "</form>";
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No available quests at the moment. Check back later!</p>";
}

// Display active quests
if ($result_active->num_rows > 0) {
    echo "<h2>Active Quests</h2>";
    echo "<ul>";
    while ($quest = $result_active->fetch_assoc()) {
        echo "<li>";
        echo "<h3>" . htmlspecialchars($quest['name']) . "</h3>";
        echo "<p>Target: Defeat " . htmlspecialchars($quest['target_count']) . " monsters</p>";
        echo "<p>Progress: " . htmlspecialchars($_SESSION['current_count'] ?? 0) . "/" . htmlspecialchars($quest['target_count']) . "</p>";
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No active quests. Accept a quest to begin!</p>";
}

// Display completed quests
if ($result_completed->num_rows > 0) {
    echo "<h2>Completed Quests</h2>";
    echo "<ul>";
    while ($quest = $result_completed->fetch_assoc()) {
        echo "<li>";
        echo "<h3>" . htmlspecialchars($quest['name']) . "</h3>";
        echo "<p>Congratulations! You completed this quest and earned " . htmlspecialchars($quest['reward']) . " gold.</p>";
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No completed quests yet. Start completing quests to see them here!</p>";
}

// Close database connection
$conn->close();
?>
