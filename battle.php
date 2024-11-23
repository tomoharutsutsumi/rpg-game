<?php
require 'db_connection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

// Initialize the player's gold if it hasn't been set
if (!isset($_SESSION['gold'])) {
    $_SESSION['gold'] = 0; // Start with 0 gold
}

// Handle the fight action
$battle_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fight'])) {
    // Simulate a monster battle outcome with random success
    $monster_defeated = rand(0, 1) === 1; // 50% chance to win

    if ($monster_defeated) {
        $battle_message = "You defeated a monster!";
        
        // Check if a quest is active
        if (isset($_SESSION['qid']) && isset($_SESSION['target_count'])) {
            $_SESSION['current_count']++;

            // Check if the quest is completed
            if ($_SESSION['current_count'] >= $_SESSION['target_count']) {
                // Add the reward to the player's gold
                $_SESSION['gold'] += $_SESSION['reward'];
                $battle_message .= " You completed the quest: " . htmlspecialchars($_SESSION['quest_name']) . "! Reward earned: " . htmlspecialchars($_SESSION['reward']) . " gold.";
                
                // Mark the quest as available again in the database
                $sql = "UPDATE quests SET status = 'available' WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $_SESSION['qid']);
                $stmt->execute();

                // Clear quest session variables
                unset($_SESSION['qid'], $_SESSION['quest_name'], $_SESSION['target_count'], $_SESSION['current_count'], $_SESSION['reward']);
            } else {
                $battle_message .= " Progress: " . $_SESSION['current_count'] . "/" . $_SESSION['target_count'] . " monsters defeated.";
            }
        } else {
            $battle_message .= " No active quest.";
        }
    } else {
        $battle_message = "You didn’t defeat the monster. Better luck next time!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Battle Page</title>
</head>
<body>
    <h1>Battle Page</h1>
    <nav>
        <a href="quests.php">Go to Quest Page</a>
    </nav>

    <h2>Gold Counter</h2>
    <p>You have <?php echo $_SESSION['gold']; ?> gold.</p>

    <h2>Current Quest</h2>
    <?php if (isset($_SESSION['qid'])): ?>
        <p><strong><?php echo htmlspecialchars($_SESSION['quest_name']); ?></strong></p>
        <p>Progress: <?php echo htmlspecialchars($_SESSION['current_count']) . "/" . htmlspecialchars($_SESSION['target_count']); ?> monsters defeated.</p>
    <?php else: ?>
        <p>No quest selected.</p>
    <?php endif; ?>

    <h2>Battle</h2>
    <form method="POST" action="battle.php">
        <button type="submit" name="fight">Fight a Monster!</button>
    </form>

    <?php if (!empty($battle_message)): ?>
        <h3>Battle Results</h3>
        <p><?php echo htmlspecialchars($battle_message); ?></p>
    <?php endif; ?>
</body>
</html>
