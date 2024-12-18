<?php
require_once 'db_connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Battle History</title>
    <link rel="stylesheet" href="player_info.css">
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $player_name = $_POST['player_name'];

    // Query to fetch player name, level, and the average level
    $sql = "SELECT 
                u.Username AS player_name, 
                c.Level AS level,
                (SELECT AVG(Level) FROM characterdetails) AS average_level
            FROM userdetails u
            INNER JOIN characterdetails c ON u.UID = c.UID
            WHERE LOWER(u.Username) = LOWER(:player_name)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':player_name', $player_name, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $player_level = $result['level'];
            $average_level = round($result['average_level'], 2); // Round average to 2 decimals

            // Display player's level, average level, and comparison
            echo "<div class='battle-history'>";
            echo "<h2>Battle History</h2>";
            echo "<p><strong>Player Name:</strong> " . htmlspecialchars($result['player_name']) . "</p>";
            echo "<p><strong>Your Level:</strong> " . htmlspecialchars($player_level) . "</p>";
            echo "<p><strong>Average Level of All Players:</strong> " . htmlspecialchars($average_level) . "</p>";
            
            // Comparison message
            if ($player_level > $average_level) {
                echo "<p>Your level is above the average.</p>";
            } elseif ($player_level < $average_level) {
                echo "<p>Your level is below the average.</p>";
            } else {
                echo "<p>Your level is exactly at the average.</p>";
            }

            echo "</div>";
        } else {
            echo "<div class='battle-history'><p>No battle history found for this player.</p></div>";
        }
    } catch (PDOException $e) {
        echo "<div class='battle-history'><p>Error retrieving data: " . $e->getMessage() . "</p></div>";
    }
} else {
    echo "<div class='battle-history'><p>Invalid request method.</p></div>";
}
?>
</body>
</html>
