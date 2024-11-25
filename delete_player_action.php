<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];

    try {
 
        $pdo->beginTransaction();

        $sqlDeleteUser = "DELETE FROM UserDetails WHERE UserName = :username";
        $stmtDeleteUser = $pdo->prepare($sqlDeleteUser);
        $stmtDeleteUser->execute(['username' => $username]);

        $pdo->commit();

        echo "Player account deleted successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "An error occurred while deleting the player. Please try again later.";
    }
}
?>

<br><br>
<form action="main_menu.html" method="GET">
    <button type="submit">Return to Main Menu</button>
</form>