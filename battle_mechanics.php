<?php
session_start();

// Include the database connection file
require_once 'db_connection.php';

// Check if UID exists in the session
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
} else {
    echo "User ID not found in session.";
    // Handle the error or redirect
    exit();
}

// Get CharacterID from SESSION
if (isset($_SESSION['character_id'])) {
    $character_id = $_SESSION['character_id'];
} else {
    echo "Character ID not found in session.";
    // Handle the error or redirect
    exit();
}

// Check if quest is selected
if (!isset($_SESSION['qid'])) {
    die("Quest not selected. Please select a quest first.");
}
$qid = $_SESSION['qid'];

// FOR TESTING: Uncomment if needed
$qid = 1;

// Fetch character details from the database
if (!isset($_SESSION['character_details'])) {
    $sql_character = "SELECT CD.*, I.Name AS ItemName, I.AttackBonus, I.DefenseBonus 
                      FROM CharacterDetails CD
                      JOIN Items I ON CD.ItemID = I.ItemID
                      WHERE CD.CharacterID = ? AND CD.UID = ?";
    $stmt_character = $pdo->prepare($sql_character);
    $stmt_character->bindParam(1, $character_id, PDO::PARAM_STR);
    $stmt_character->bindParam(2, $uid, PDO::PARAM_INT);
    $stmt_character->execute();
    $character = $stmt_character->fetch();

    if (!$character) {
        die("Character not found. Please create a character first.");
    }

    // Store character details in session
    $_SESSION['character_details'] = $character;
    $_SESSION['player_health'] = 50 + $character['Level'] * 10; // Set initial player health
} else {
    $character = $_SESSION['character_details'];
}

// Fetch monster details from the database
if (!isset($_SESSION['monster_details'])) {
    $sql_monster = "SELECT M.*, I.Name AS ItemName, I.AttackBonus, I.DefenseBonus 
                    FROM Monsters M
                    JOIN Items I ON M.ItemID = I.ItemID
                    WHERE M.QID = ?";
    $stmt_monster = $pdo->prepare($sql_monster);
    $stmt_monster->bindParam(1, $qid, PDO::PARAM_INT);
    $stmt_monster->execute();
    $monster = $stmt_monster->fetch();

    if (!$monster) {
        die("Monster not found for the selected quest.");
    }

    // Store monster details in session
    $_SESSION['monster_details'] = $monster;
    $_SESSION['monster_health'] = 50 + $monster['Level'] * 5; // Set initial monster health
} else {
    $monster = $_SESSION['monster_details'];
}

// Set player and monster health from session
$player_health = &$_SESSION['player_health'];
$monster_health = &$_SESSION['monster_health'];

// Calculate player stats
$player_attack = 30 + $character['Level'] + $character['AttackBonus'];
$player_defense = 5 + $character['Level'] + $character['DefenseBonus'];

// Calculate monster stats
$monster_attack = 28 + $monster['Level'] + $monster['AttackBonus'];
$monster_defense = 4 + $monster['Level'] + $monster['DefenseBonus'];

// Function to handle battle turn
function battle_turn(&$attacker, &$defender) {
    // Determine if the attack is a critical hit (33% chance)
    $is_critical = rand(1, 100) <= 33;
    $critical_multiplier = $is_critical ? 1.25 : 1;

    // Calculate damage dealt
    $damage = max(1, (($attacker['attack'] * $critical_multiplier) - $defender['defense']) / 2);
    $defender['health'] = max(0, $defender['health'] - $damage); // Ensure health does not drop below 0

    return ['damage' => $damage, 'is_critical' => $is_critical];
}

// Player's and Monster's stats as arrays
$player_stats = [
    'attack' => $player_attack,
    'defense' => $player_defense,
    'health' => &$player_health // Pass health by reference
];

$monster_stats = [
    'attack' => $monster_attack,
    'defense' => $monster_defense,
    'health' => &$monster_health // Pass health by reference
];

// Handle battle logic based on user action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == 'fight') {
        // Player's turn to attack the monster
        $turn_result = battle_turn($player_stats, $monster_stats);
        $player_damage = $turn_result['damage'];
        $is_critical = $turn_result['is_critical'];

        $_SESSION['battle_status'] = "You dealt $player_damage damage to the monster.";
        if ($is_critical) {
            $_SESSION['battle_status'] .= " It was a critical hit!";
        }
        $_SESSION['next_turn'] = 'monster';
    } elseif ($_POST['action'] == 'monster_turn') {
        // Monster's turn to attack the player
        $turn_result = battle_turn($monster_stats, $player_stats);
        $monster_damage = $turn_result['damage'];
        $_SESSION['battle_status'] = "The monster dealt $monster_damage damage to you.";
        if ($is_critical) {
            $_SESSION['battle_status'] .= " It was a critical hit!";
        }
        $_SESSION['next_turn'] = 'player';
    }

    // Check if the player or monster health is 0 and set the outcome
    if ($player_health <= 0) {
        $_SESSION['battle_status'] = "You have been defeated by the monster. Game over.
        <br>
        <form action=\"quest_list.php\" method=\"POST\">
            <input type=\"hidden\" name=\"action\" value=\"fight\">
            <button type=\"submit\">Back to Quest List</button>
        </form>";

        $_SESSION['next_turn'] = null;

        // Cleanup session variables
        unset($_SESSION['battle_state']);
        unset($_SESSION['player_health']);
        unset($_SESSION['monster_details']);
        unset($_SESSION['monster_health']);
    } elseif ($monster_health <= 0) {
        // Increase character level by 1
        $_SESSION['character_details']['Level'] += 1;
    
        // Store the new level in a variable
        $new_level = $_SESSION['character_details']['Level'];
        
        // Update character level in the database
        $sql_update_level = "UPDATE CharacterDetails SET Level = Level + 1 WHERE CharacterID = ?";
        $stmt_update_level = $pdo->prepare($sql_update_level);
        $stmt_update_level->execute([$_SESSION['character_details']['CharacterID']]);
    
        // Set the battle status with level up message
        $_SESSION['battle_status'] = "Congratulations! You have defeated the monster.
        <br>
        You level up, your character is now level $new_level!
        <br>
        <form action=\"quest_list.php\" method=\"POST\">
            <input type=\"hidden\" name=\"action\" value=\"fight\">
            <button type=\"submit\">Back to Quest List</button>
        </form>";

        $_SESSION['next_turn'] = null;

        // Cleanup session variables
        unset($_SESSION['battle_state']);
        unset($_SESSION['player_health']);
        unset($_SESSION['monster_details']);
        unset($_SESSION['monster_health']);
    }
    

    // Update health in session after each turn
    $_SESSION['player_health'] = $player_stats['health'];
    $_SESSION['monster_health'] = $monster_stats['health'];

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Display character and monster details
$player_health = $_SESSION['player_health'];
$monster_health = $_SESSION['monster_health'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Battle Mechanics</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="login.html">Login</a></li>
            <li><a href="register.html">Register</a></li>
        </ul>
    </nav>
    <h1>Battle</h1>

    <!-- Display Character Details -->
    <h2>Character Details</h2>
    <table border="1">
        <tr>
            <th>Character ID</th>
            <th>Level</th>
            <th>Item</th>
            <th>Health</th>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($character['CharacterID'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($character['Level'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($character['ItemName'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($player_health); ?></td>
        </tr>
    </table>

    <!-- Display Monster Details -->
    <h2>Monster Details</h2>
    <table border="1">
        <tr>
            <th>Monster ID</th>
            <th>Level</th>
            <th>Item</th>
            <th>Health</th>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($monster['MonsterID'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($monster['Level'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($monster['ItemName'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($monster_health); ?></td>
        </tr>
    </table>

    <!-- Display Battle Status -->
    <?php
    if (isset($_SESSION['battle_status'])) {
        echo "<p>" . $_SESSION['battle_status'] . "</p>";
        unset($_SESSION['battle_status']);
    }
    ?>

    <!-- Battle Action Form -->
    <form action="" method="POST">
        <?php if (!isset($_SESSION['next_turn']) || $_SESSION['next_turn'] == 'player'): ?>
            <input type="hidden" name="action" value="fight">
            <button type="submit" class="fight_button">Fight!</button>
        <?php elseif ($_SESSION['next_turn'] == 'monster'): ?>
            <input type="hidden" name="action" value="monster_turn">
            <button type="submit" class="fight_button">Monster's Turn</button>
        <?php endif; ?>
    </form>

