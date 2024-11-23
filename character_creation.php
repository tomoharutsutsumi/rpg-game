<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Character Creation</title>
</head>
<body>
    <h1>Create Your Character</h1>
    <form action="create_character.php" method="POST">
        <label for="char_id">Character ID:</label>
        <input type="text" id="char_id" name="char_id" required>
        <br><br>
        <label for="starter_item">Select Starter Item:</label>
        <select id="starter_item" name="starter_item" required>
            <option value="sword">Sword</option>
            <option value="shield">Shield</option>
        </select>
        <br><br>
        <button type="submit">Create Character</button>
    </form>
</body>
</html>
