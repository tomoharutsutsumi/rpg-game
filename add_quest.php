<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html"); // Redirect if not logged in
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "game_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $questName = $_POST['questName'];
    $location = $_POST['location'];
    $level_up_reward = $_POST['level_up_reward'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO Quest (questName, location, level_up_reward, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $questName, $location, $level_up_reward, $description);

    if ($stmt->execute()) {
        echo "New quest added successfully!";
        echo "<br><a href='admin_dashboard.php'>Back to Dashboard</a>";
    } else {
        echo "Error adding quest: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
