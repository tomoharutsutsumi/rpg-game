<?php
session_start();
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "rpg"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adminUsername = $_POST['username'];
    $adminPassword = $_POST['password'];

    // Fetch user data from database
    $stmt = $conn->prepare("SELECT * FROM usercredentials WHERE userName = ?");
    $stmt->bind_param("s", $adminUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Check password (ensure the column name is correct)
        if (password_verify($adminPassword, $user['UserPassword'])) {
            // Password is correct, start session
            $_SESSION['admin_username'] = $user['UserName'];

            // Now, check if this user is also in the adminuid table
            $stmt2 = $conn->prepare("SELECT UID FROM adminuid WHERE user_credentials_username = ?");
            $stmt2->bind_param("s", $adminUsername);  // Use the correct variable here
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            if ($result2->num_rows > 0) {
                // Admin credentials are valid, fetch UID
                $row = $result2->fetch_assoc();
                $_SESSION['admin_id'] = $row['UID']; // Store admin UID in session
                
                // Redirect to the dashboard
                header("Location: admin_dashboard.php");
                exit();  // Ensure no further code is executed
            } else {
                echo "Admin not found in adminuid table.";
            }

            $stmt2->close();
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
