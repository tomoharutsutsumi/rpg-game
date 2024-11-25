<!DOCTYPE html>
<html>
<head>
    <title>Delete Player</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            margin-top: 100px;
        }
        button, input {
            padding: 10px 20px;
            font-size: 16px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <h1>Delete Player Account</h1>
    <form action="delete_player_action.php" method="POST">
        <label for="username">Enter Username:</label>
        <input type="text" id="username" name="username" required>
        <br><br>
        <button type="submit">Delete Player</button>
    </form>
</body>
</html>
