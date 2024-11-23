<?php
require 'db_connection.php';
session_start();

// Fetch all quests from the database
$sql_all_quests = "SELECT * FROM quests";
$result_all_quests = $conn->query($sql_all_quests);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Quests Page</title>
</head>
<body>
    <h1>Quest Page</h1>

    <h2>Available Quests</h2>
    <?php if ($result_all_quests->num_rows > 0): ?>
        <ul>
            <?php while ($quest = $result_all_quests->fetch_assoc()): ?>
                <?php if ($quest['status'] === 'available'): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($quest['name']); ?></h3>
                        <p><?php echo htmlspecialchars($quest['description']); ?></p>
                        <p>Target: Defeat <?php echo htmlspecialchars($quest['target_count']); ?> monsters</p>
                        <p>Reward: <?php echo htmlspecialchars($quest['reward']); ?> gold</p>
                        <form method="POST" action="select_quest.php">
                            <input type="hidden" name="quest_id" value="<?php echo $quest['id']; ?>">
                            <button type="submit">Accept Quest</button>
                        </form>
                    </li>
                <?php endif; ?>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No quests available at the moment. Check back later!</p>
    <?php endif; ?>

    <h2>Current Quest</h2>
    <?php if (isset($_SESSION['qid'])): ?>
        <p><strong><?php echo htmlspecialchars($_SESSION['quest_name']); ?></strong></p>
        <p>Progress: <?php echo htmlspecialchars($_SESSION['current_count']) . "/" . htmlspecialchars($_SESSION['target_count']); ?> monsters defeated.</p>
    <?php else: ?>
        <p>No active quest selected.</p>
    <?php endif; ?>

    <h2>Repeatable Quests</h2>
    <?php
    // Reset the pointer for the result set
    $result_all_quests->data_seek(0);
    ?>
    <?php if ($result_all_quests->num_rows > 0): ?>
        <ul>
            <?php while ($quest = $result_all_quests->fetch_assoc()): ?>
                <?php if ($quest['status'] !== 'available'): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($quest['name']); ?></h3>
                        <p><?php echo htmlspecialchars($quest['description']); ?></p>
                        <p>Target: Defeat <?php echo htmlspecialchars($quest['target_count']); ?> monsters</p>
                        <p>Reward: <?php echo htmlspecialchars($quest['reward']); ?> gold</p>
                        <form method="POST" action="select_quest.php">
                            <input type="hidden" name="quest_id" value="<?php echo $quest['id']; ?>">
                            <button type="submit">Repeat Quest</button>
                        </form>
                    </li>
                <?php endif; ?>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No repeatable quests available at the moment.</p>
    <?php endif; ?>

    <nav>
        <a href="battle.php">Go to Battle Page</a>
    </nav>
</body>
</html>

<?php
$conn->close();
?>
