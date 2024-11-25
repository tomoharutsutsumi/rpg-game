<!DOCTYPE html>
<html>
<head>
    <title>Achievements</title>
    <link rel="stylesheet" href="achievements_styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #D4B49A; /* Beige background */
            color: #333;
            margin: 0;
            padding: 0;
        }
        .achievement-list {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #E8C6A6; /* Light brown background */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #5C4033; /* Dark brown text */
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background-color: #A0522D; /* Sienna color for list items */
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            color: #FFF;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        p {
            font-size: 1.1em;
            color: #5C4033; /* Dark brown text */
            text-align: center;
        }
        button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            font-size: 1em;
            color: #fff;
            background-color: #8B4513; /* SaddleBrown for button */
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #5C4033; /* Darker brown on hover */
        }
    </style>
</head>
<body>
    <div class="achievement-list">
        <h1>Game Stats</h1>
        <?php
session_start();
require_once 'db_connection.php';

// Query to find players who have completed all quests
$query = "SELECT DISTINCT p.UID FROM playerinfo p WHERE NOT EXISTS (SELECT q.QID FROM quest q WHERE NOT EXISTS (SELECT * FROM playerinfo pi WHERE pi.completedQID = q.QID AND pi.UID = p.UID))";
$stmt = $pdo->prepare($query);
$stmt->execute();
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($players) {
    echo "<ul>";
    foreach ($players as $player) {
        echo "<li>Player UID: " . htmlspecialchars($player['UID']) . " has completed all quests.</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No players have completed all quests yet.</p>";
}

// Query to get the max level reached by players
$query_max_level = "SELECT MAX(Level) AS MaxLevel FROM CharacterDetails";
$stmt_max_level = $pdo->prepare($query_max_level);
$stmt_max_level->execute();
$max_level = $stmt_max_level->fetch(PDO::FETCH_ASSOC);

if ($max_level) {
    echo "<ul>";
    echo "<li>Highest Level Reached by Any Player: " . htmlspecialchars($max_level['MaxLevel']) . "</li>";
    echo "</ul>";
}

// Query to get the item with the highest attack bonus
$query_max_attack = "SELECT Name, AttackBonus FROM items WHERE AttackBonus = (SELECT MAX(AttackBonus) FROM items)";
$stmt_max_attack = $pdo->prepare($query_max_attack);
$stmt_max_attack->execute();
$max_attack_item = $stmt_max_attack->fetch(PDO::FETCH_ASSOC);

if ($max_attack_item) {
    echo "<ul>";
    echo "<li>Item with Highest Attack Bonus: " . htmlspecialchars($max_attack_item['Name']) . " (Attack Bonus: " . htmlspecialchars($max_attack_item['AttackBonus']) . ")</li>";
    echo "</ul>";
}

// Query to get the item with the highest defense bonus
$query_max_defense = "SELECT Name, DefenseBonus FROM items WHERE DefenseBonus = (SELECT MAX(DefenseBonus) FROM items)";
$stmt_max_defense = $pdo->prepare($query_max_defense);
$stmt_max_defense->execute();
$max_defense_item = $stmt_max_defense->fetch(PDO::FETCH_ASSOC);

if ($max_defense_item) {
    echo "<ul>";
    echo "<li>Item with Highest Defense Bonus: " . htmlspecialchars($max_defense_item['Name']) . " (Defense Bonus: " . htmlspecialchars($max_defense_item['DefenseBonus']) . ")</li>";
    echo "</ul>";
}
?>
        
        <!-- Button to return to main menu -->
        <form action="main_menu.html" method="GET">
            <button type="submit">Return to Main Menu</button>
        </form>
    </div>
</body>
</html>
