<?php
session_start();

// Include the database connection file
require_once 'db_connection.php';

// Simulate a logged-in user with UID = 5
$_SESSION['uid'] = 5;

// Simulate a logged-in user with QID = 1
$_SESSION['qid'] = 1;

// Simulate a logged-in user
if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];
} else {
    die("User not logged in. Please log in to continue.");
}

// Get the QID from the selected quest (assumed to be stored in session after selection)
if (isset($_SESSION['qid'])) {
    $qid = $_SESSION['qid'];
} else {
    die("Quest not selected. Please select a quest first.");
}

// Get character details from database using UID
$sql_character = "SELECT * FROM CharacterDetails WHERE UID = ?";
$stmt_character = $pdo->prepare($sql_character);
$stmt_character->bindParam(1, $uid, PDO::PARAM_INT);
$stmt_character->execute();
$character = $stmt_character->fetch();

if (!$character) {
    die("Character not found. Please create a character first.");
}

$char_id = $character['CharacterID'];
$player_level = $character['Level'];
$item_id = $character['ItemID'];

// Get character item details from Items table
$sql_item = "SELECT * FROM Items WHERE ItemID = ?";
$stmt_item = $pdo->prepare($sql_item);
$stmt_item->bindParam(1, $item_id, PDO::PARAM_INT);
$stmt_item->execute();
$item = $stmt_item->fetch();

if (!$item) {
    die("Character Item not found.");
}

// Player stats
$player_health = 50 + $player_level * 10; // Base health plus level multiplier
$player_attack = 10 + $character['Level'] + $item['AttackBonus']; // Base attack plus level and item attack bonus
$player_defense = 5 + $character['Level'] + $item['DefenseBonus']; // Base defense plus level and item defense bonus


// Get monster details from the Monsters table using QID
$sql_monster = "SELECT * FROM Monsters WHERE QID = ? LIMIT 1";
$stmt_monster = $pdo->prepare($sql_monster);
$stmt_monster->bindParam(1, $qid, PDO::PARAM_INT);
$stmt_monster->execute();
$monster = $stmt_monster->fetch();

if (!$monster) {
    die("Monster not found for the selected quest.");
}

$monster_item_id = $monster['ItemID'];

// Get monster item details from Items table
$sql_monster_item = "SELECT * FROM Items WHERE ItemID = ?";
$stmt_monster_item = $pdo->prepare($sql_monster_item);
$stmt_monster_item->bindParam(1, $monster_item_id, PDO::PARAM_INT);
$stmt_monster_item->execute();
$monster_item = $stmt_monster_item->fetch();

if (!$monster_item) {
    die("Monster item not found.");
}

// Monster stats
$monster_health = 50 + $monster['Level'] * 8; // Example health calculation based on level
$monster_attack = 8 + $monster['Level'] + $monster_item['AttackBonus']; // Base attack plus level and item attack bonus
$monster_defense = 4 + $monster['Level'] + $monster_item['DefenseBonus']; // Base defense plus level and item defense bonus


// Function to handle battle turn
function battle_turn(&$attacker, &$defender) {
    $damage = max(0, $attacker['attack'] - $defender['defense']);
    $defender['health'] -= $damage;
    return $damage;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'fight') {
    // Monster's turn
    $monster_damage = battle_turn(
        ['attack' => $monster_attack, 'health' => &$player_health],
        ['attack' => $player_attack, 'health' => &$monster_health]
    );

    // Player's turn
    $player_damage = battle_turn(
        ['attack' => $player_attack, 'health' => &$monster_health],
        ['attack' => $monster_attack, 'health' => &$player_health]
    );

    // Determine the outcome and store it in session
    if ($player_health <= 0) {
        $_SESSION['battle_result'] = "You have been defeated by the " . htmlspecialchars($monster_item['Name']) . ".";
    } elseif ($monster_health <= 0) {
        $_SESSION['battle_result'] = "You have defeated the " . htmlspecialchars($monster_item['Name']) . "!";

        // Update character level upon quest completion
        $new_level = $player_level + 1;
        $sql_update_level = "UPDATE CharacterDetails SET Level = ? WHERE CharacterID = ?";
        $stmt_update = $pdo->prepare($sql_update_level);
        $stmt_update->bindParam(1, $new_level, PDO::PARAM_INT);
        $stmt_update->bindParam(2, $char_id, PDO::PARAM_INT);
        if ($stmt_update->execute()) {
            $_SESSION['level_up'] = "Congratulations! You have leveled up to level " . htmlspecialchars($new_level) . ".";
        } else {
            $_SESSION['level_up'] = "Error updating character level.";
        }
    } else {
        // Store ongoing battle status in session
        $_SESSION['battle_status'] = "Your Health: " . htmlspecialchars($player_health) . "<br>" .
                                     htmlspecialchars($monster_item['Name']) . " Health: " . htmlspecialchars($monster_health);
    }

    // Redirect back to the battle page to display results
    header("Location: battle.php");
    exit();
}
?>
