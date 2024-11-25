
<!DOCTYPE html>
<html>
<head>
    <title>Quest Introduction</title>
    <link rel="stylesheet" href="quest_intro_styles.css">
</head>
<body>
    <div class="quest-details">
        <?php
session_start();

if (isset($_SESSION['intro_text']) && isset($_SESSION['selected_quest'])) {
    echo "<h1>Quest Introduction</h1>";
    echo "<p>" . htmlspecialchars($_SESSION['intro_text']) . "</p>";
    echo "<form action='battle.php' method='POST'>";
        echo "<input type='hidden' name='qid' value='" . $_SESSION['selected_quest'] . "'>";
        echo "<button type='submit'>Start Battle</button>";
    echo "</form>";
} else {
    echo "No quest selected.";
    echo "<a href='quests.php'>Back to Quest Selection</a>";
}
?>
    </div>
</body>
</html>
