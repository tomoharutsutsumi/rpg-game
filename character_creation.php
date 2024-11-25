<!DOCTYPE html>
<head>
    <title>Character Creation</title>
    <link rel="stylesheet" href="character_creation_styles.css"
</head>
<body>
    <p style="color: green; font-weight: bold;">User created successfully! You can now continue with your adventure.</p>

    <h1>Create Your Character</h1>

    <?php
    session_start();
    if (isset($_SESSION['error_message'])) {
        echo "<p style='color: red;'>" . $_SESSION['error_message'] . "</p>";
        unset($_SESSION['error_message']);
    }
    ?>

    <form action="create_character.php" method="POST">
        <!-- Character ID Input -->
        <label for="char_id">Character ID:</label>
        <input type="text" id="char_id" name="char_id" required>
        <br><br>

        <!-- Starter Item Selection -->
        <label for="starter_item">Select Starter Item:</label>
        <select id="starter_item" name="starter_item" required>
            <option value="sword">Sword</option>
            <option value="shield">Shield</option>
            <option value="bow">Bow</option>
            <option value="axe">Axe</option>
            <option value="spear">Spear</option>
            <option value="dagger">Dagger</option>
            <option value="mace">Mace</option>
            <option value="staff">Staff</option>
            <option value="crossbow">Crossbow</option>
            <option value="halberd">Halberd</option>
        </select>
        <br><br>

        <!-- Submit Button -->
        <button type="submit">Create Character</button>
    </form>
</body>
</html>
