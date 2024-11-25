<?php
session_start();

// Check if the admin is logged in
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

// Fetch the maximum QID value
$sql = "SELECT MAX(QID) AS max_QID FROM Quest";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $newQID = $row['max_QID'] + 1;  // Increment the max QID by 1
} else {
    // Handle error if no results or failed query
    die("Error fetching max QID: " . $conn->error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $Location = $_POST['location'];  // Assuming 'location' is a field in your form
    $IntroText = $_POST['intro_text'];  // Assuming 'intro_text' is a field in your form

    // Prepare SQL query, using the manually generated QID
    $stmt = $conn->prepare("INSERT INTO Quest (QID, Location, IntroText) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $newQID, $Location, $IntroText);  // Bind new QID, Location, and IntroText

    if ($stmt->execute()) {
        echo "New quest added successfully!";
        echo "<br><a href='main_menu.php'>Back to Main Menu</a>";
    } else {
        echo "Error adding quest: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
