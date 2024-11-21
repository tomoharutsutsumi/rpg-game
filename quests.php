<?php
// Include database configuration
require_once 'config.php';

// Establish database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available quests
$query = "SELECT QID, Location, Reward, IntroText FROM Quests";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<h1>Available Quests</h1><ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>";
        echo "<strong>Location:</strong> " . $row['Location'] . "<br>";
        echo "<strong>Reward:</strong> Level-ups: " . $row['Reward'] . "<br>";
        echo "<form action='select_quest.php' method='POST'>";
        echo "<input type='hidden' name='qid' value='" . $row['QID'] . "'>";
        echo "<button type='submit'>Select Quest</button>";
        echo "</form>";
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "No quests available.";
}

$conn->close();
?>
