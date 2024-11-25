<?php
session_start();  // Start the session
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");  // Redirect if session not set
    exit();
}


$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "rpg"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);  // Error handling
}

// Add SQL query to fetch quests
$sql = "SELECT QID, Location, IntroText FROM Quest";
$result = $conn->query($sql); // Execute the query

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $conn->error); // Output error message if the query fails
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Welcome, Admin!</h2>
    <h3>Quest Management</h3>

    <h4>Available Quests</h4>
    <table border="1">
        <tr>
            <th>Quest ID</th>
            <th>Location</th>
            <th>Intro Text</th>
        </tr>
        <?php
        // Check if there are any rows to display
        if ($result->num_rows > 0) {
            // Loop through and display each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['QID'] . "</td>
                        <td>" . $row['Location'] . "</td>
                        <td>" . $row['IntroText'] . "</td>
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

        <label for="intro_text">Intro Text:</label>
        <textarea name="intro_text" id="intro_text" required></textarea><br><br>

        <input type="submit" value="Add Quest">
    </form>
</body>
</html>

<?php
$conn->close();
?>
