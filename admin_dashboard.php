<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html"); // Redirect if not logged in
}

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "game_db"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM Quest";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Welcome, Admin</h2>
    <h3>Quest Management</h3>

    <h4>Available Quests</h4>
    <table border="1">
        <tr>
            <th>Quest ID</th>
            <th>Location</th>
            <th>Level-up Reward</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['questID'] . "</td>
                        <td>" . $row['location'] . "</td>
                        <td>" . $row['level_up_reward'] . "</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No quests available</td></tr>";
        }
        ?>
    </table>

    <h4>Add New Quest</h4>
    <form action="add_quest.php" method="POST">
        <label for="location">Quest Location:</label>
        <input type="text" name="location" id="location" required><br><br>

        <label for="level_up_reward">Level-up Reward:</label>
        <input type="number" name="level_up_reward" id="level_up_reward" required><br><br>

        <input type="submit" value="Add Quest">
    </form>
</body>
</html>

<?php
$conn->close();
?>