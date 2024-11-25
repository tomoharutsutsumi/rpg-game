<?php
session_start();
require_once 'db_connection.php';

$query = "SELECT QID, Location, IntroText FROM quest";
$stmt = $pdo->prepare($query);
$stmt->execute();
$quests = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h1>Select a Quest</h1>";
if ($quests) {
    echo "<ul>";
    foreach ($quests as $quest) {
        echo "<li>";
            echo "<strong>Location:</strong> " . htmlspecialchars($quest['Location']) . "<br>";
            echo "<form action='select_quest.php' method='POST'>";
                echo "<input type='hidden' name='qid' value='" . $quest['QID'] . "'>";
                echo "<button type='submit'>Select Quest</button>";
            echo "</form>";
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No quests available at the moment.</p>";
}
?>
