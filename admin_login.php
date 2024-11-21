<?php
session_start();
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "game_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adminUsername = $_POST['username'];
    $adminPassword = $_POST['password'];

    // Fetch user data from database
    $stmt = $conn->prepare("SELECT * FROM UserCredential WHERE username = ?");
    $stmt->bind_param("s", $adminUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($adminPassword, $user['password'])) {
            // Password is correct, start session
            $_SESSION['admin_id'] = $user['userID'];
            $_SESSION['admin_username'] = $user['username'];
            header("Location: admin_dashboard.php");
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "No such user found!";
    }
    $stmt->close();
}

$conn->close();
?>