<?php
require_once 'db_connection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

// Handle character creation form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $char_id = trim($_POST['char_id']); // Sanitize character ID input
    $starter_item = trim($_POST['starter_item']); // Sanitize starter item
    $user_id = $_SESSION['user_id']; // Get the user ID from the session

    // Check if the character ID is unique
    $check_sql = "SELECT id FROM characters WHERE char_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $char_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Character ID is already taken. Please choose another.";
    } else {
        // Insert the new character into the database
        $stmt->close();
        $sql = "INSERT INTO characters (user_id, char_id, starter_item) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $user_id, $char_id, $starter_item);

        if ($stmt->execute()) {
            echo "Character created successfully!";
            header("Location: battle.php");
            exit();
        } else {
            echo "Error: Could not create character. Please try again.";
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Character</title>
</head>
<body>
    <h1>Create Your Character</h1>
    <form method="POST" action="create_character.php">
        <label for="char_id">Character Name:</label>
        <input type="text" id="char_id" name="char_id" required>
        <br><br>
        <label for="starter_item">Choose a Starter Item:</label>
        <select id="starter_item" name="starter_item" required>
            <option value="Sword">Sword</option>
            <option value="Shield">Shield</option>
            <option value="Bow">Bow</option>
            <option value="Staff">Staff</option>
        </select>
        <br><br>
        <button type="submit">Create Character</button>
    </form>
</body>
</html>
