<?php
// Include database configuration
require_once 'config.php';

// Establish database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get quest ID and player ID (assume PlayerID is passed via session or static for simplicity)
$qid = $_POST['qid'];
$characterID = 1; // Replace with session variable for actual user

// Update the player's active quest
$updateQuery = "UPDATE CharacterDetails SET ActiveQuest = ? WHERE CharacterID = ?";
$stmt = $conn->prepare($updateQuery);
$stmt->bind_param("ii", $qid, $characterID);

if ($stmt->execute()) {
    // Fetch and display quest intro text
    $questQuery = "SELECT IntroText FROM Quests WHERE QID = ?";
    $stmt2 = $conn->prepare($questQuery);
    $stmt2->bind_param("i", $qid);
    $stmt2->execute();
    $stmt2->bind_result($introText);
    $stmt2->fetch();

    echo "<h1>Quest Selected</h1>";
    echo "<p>$introText</p>";
    echo "<a href='quests.php'>Back to Quests</a>";
    
    $stmt2->close();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
