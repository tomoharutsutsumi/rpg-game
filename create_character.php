<?php
session_start();

// Simulate a logged-in user with UID = 5
$_SESSION['uid'] = 5;

// Include the database connection file
require_once 'db_connection.php';

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $char_id = $_POST['char_id'];
    $starter_item = $_POST['starter_item'];

    // Assuming UID is stored in session after login
    if (isset($_SESSION['uid'])) {
        $uid = $_SESSION['uid'];
    } else {
        die("User not logged in. Please log in to create a character.");
    }

    // Setting default level to 1
    $level = 1;

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
        $sql_insert = "INSERT INTO CharacterDetails (CharacterID, UID, Level, Item) VALUES (?, ?, ?, ?)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->bindParam(1, $char_id, PDO::PARAM_INT);
        $stmt_insert->bindParam(2, $uid, PDO::PARAM_INT);
        $stmt_insert->bindParam(3, $level, PDO::PARAM_INT);
        $stmt_insert->bindParam(4, $starter_item, PDO::PARAM_STR);

        if ($stmt_insert->execute()) {
            // Insert the inventory for the new character into CharacterInventory
            $inventory_id = 100 + $uid; // InventoryID is 100 + UID
            $sql_inventory = "INSERT INTO CharacterInventory (CharacterID, InventoryID) VALUES (?, ?)";
            $stmt_inventory = $pdo->prepare($sql_inventory);
            $stmt_inventory->bindParam(1, $char_id, PDO::PARAM_INT);
            $stmt_inventory->bindParam(2, $inventory_id, PDO::PARAM_INT);

            if ($stmt_inventory->execute()) {
                // Insert the initial inventory record into Inventory table with the selected starter item
                $sql_inventory_table = "INSERT INTO Inventory (InventoryID, Size, Item) VALUES (?, ?, ?)";
                $stmt_inventory_table = $pdo->prepare($sql_inventory_table);
                $size = 10; // Default size
                $initial_item = $starter_item; // Use the starter item selected by the user
                $stmt_inventory_table->bindParam(1, $inventory_id, PDO::PARAM_INT);
                $stmt_inventory_table->bindParam(2, $size, PDO::PARAM_INT);
                $stmt_inventory_table->bindParam(3, $initial_item, PDO::PARAM_STR);

                if ($stmt_inventory_table->execute()) {
                    echo "Character, CharacterInventory, and Inventory created successfully!";
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
