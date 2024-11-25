<?php
session_start(); // Start the session
// create_character.php

// Include the database connection file
require_once 'db_connection.php';

if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    // Use $uid as needed
} else {
    echo "User ID not found in session.";
    // Handle the error as needed
}

// For testing
// $uid = 999;

// Process the form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $char_id = trim($_POST['char_id']);
    $starter_item = $_POST['starter_item'];

    // Validate inputs
    if (empty($char_id) || empty($starter_item)) {
        echo "Please fill in all fields.";
        exit();
    }

    // Setting default level to 1
    $level = 1;

    // Items description
    switch ($starter_item) {
        case 'sword':
            $effect = "+10 attack, +5 defense";
            $item_id = 1;
            break;
        case 'shield':
            $effect = "+5 attack, +15 defense";
            $item_id = 2;
            break;
        case 'bow':
            $effect = "+8 attack, +4 defense";
            $item_id = 3;
            break;
        case 'axe':
            $effect = "+12 attack, +3 defense";
            $item_id = 4;
            break;
        case 'spear':
            $effect = "+9 attack, +6 defense";
            $item_id = 5;
            break;
        case 'dagger':
            $effect = "+7 attack, +2 defense";
            $item_id = 6;
            break;
        case 'mace':
            $effect = "+11 attack, +8 defense";
            $item_id = 7;
            break;
        case 'staff':
            $effect = "+6 attack, +10 defense";
            $item_id = 8;
            break;
        case 'crossbow':
            $effect = "+10 attack, +4 defense";
            $item_id = 9;
            break;
        case 'halberd':
            $effect = "+14 attack, +7 defense";
            $item_id = 10;
            break;
    }

    // Check if the character ID is unique
    $sql_check = "SELECT * FROM CharacterDetails WHERE CharacterID = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(1, $char_id, PDO::PARAM_INT);
    $stmt_check->execute();
    $result = $stmt_check->fetch();

    if ($result) {
        $_SESSION['error_message'] = "Character ID already exists. Please choose a different ID.";
        header("Location: character_creation.php");
        exit();
    } else {
        // Insert the new character into the database
        $sql_insert = "INSERT INTO CharacterDetails (CharacterID, UID, Level, ItemID) VALUES (?, ?, ?, ?)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->bindParam(1, $char_id, PDO::PARAM_INT);
        $stmt_insert->bindParam(2, $uid, PDO::PARAM_INT);
        $stmt_insert->bindParam(3, $level, PDO::PARAM_INT);
        $stmt_insert->bindParam(4, $item_id, PDO::PARAM_STR);

        if ($stmt_insert->execute()) {
            // Store CharacterID in the session
            $_SESSION['character_id'] = $char_id;
            
            // Insert the inventory for the new character into CharacterInventory
            $inventory_id = 100 + $uid; // InventoryID is 100 + CharacterID
            $sql_inventory = "INSERT INTO CharacterInventory (CharacterID, InventoryID) VALUES (?, ?)";
            $stmt_inventory = $pdo->prepare($sql_inventory);
            $stmt_inventory->bindParam(1, $char_id, PDO::PARAM_INT);
            $stmt_inventory->bindParam(2, $inventory_id, PDO::PARAM_INT);

            if ($stmt_inventory->execute()) {
                // Insert the initial inventory record into Inventory table with the selected starter item
                $sql_inventory_table = "INSERT INTO Inventory (InventoryID, Size, ItemID) VALUES (?, ?, ?)";
                $stmt_inventory_table = $pdo->prepare($sql_inventory_table);
                $size = 10; // Default size, you can modify this as needed
                $stmt_inventory_table->bindParam(1, $inventory_id, PDO::PARAM_INT);
                $stmt_inventory_table->bindParam(2, $size, PDO::PARAM_INT);
                $stmt_inventory_table->bindParam(3, $item_id, PDO::PARAM_STR);

                if ($stmt_inventory_table->execute()) {
                    echo "Character, CharacterInventory, and Inventory created successfully!<br><br>";
                    echo "<strong>Character Details:</strong><br>";
                    echo "Character ID: " . htmlspecialchars($char_id) . "<br>";
                    echo "Level: " . htmlspecialchars($level) . "<br>";
                    echo "Starter Item: " . htmlspecialchars($starter_item) . "<br>";
                    echo "Item Effects: " . htmlspecialchars($effect) . "<br>" . "<br>";
                    //--------------Quest Selection Part---------------
                    echo "<a href='quests.php'><button>Continue to your aventure</button></a>";
                } else {
                    echo "Character and CharacterInventory created, but there was an error creating the inventory record: " . $stmt_inventory_table->errorInfo()[2];
                }

                $stmt_inventory_table->closeCursor();
            } else {
                echo "Character created, but there was an error creating the inventory: " . $stmt_inventory->errorInfo()[2];
            }

            $stmt_inventory->closeCursor();
        } else {
            echo "Error: " . $stmt_insert->errorInfo()[2];
        }
    }

    // Close statements
    $stmt_check->closeCursor();
    $stmt_insert->closeCursor();
}
?>
