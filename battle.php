<!DOCTYPE html>
<html>
<head>
    <title>Battle Page</title>
</head>
<body>
    <h1>Battle!</h1>

    <?php
    session_start();

    // Display battle results or status if available
    if (isset($_SESSION['battle_result'])) {
        echo "<p>" . $_SESSION['battle_result'] . "</p>";
        unset($_SESSION['battle_result']);
    }

    if (isset($_SESSION['level_up'])) {
        echo "<p>" . $_SESSION['level_up'] . "</p>";
        unset($_SESSION['level_up']);
    }

    if (isset($_SESSION['battle_status'])) {
        echo "<p>" . $_SESSION['battle_status'] . "</p>";
        unset($_SESSION['battle_status']);
    }
    ?>

    <form action="battle_mechanics.php" method="POST">
        <input type="hidden" name="action" value="fight">
        <button type="submit">Fight!</button>
    </form>
</body>
</html>
