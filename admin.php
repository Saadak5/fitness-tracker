<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        form {
            width: 300px;
            margin: auto;
        }
        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            margin: 10px 0;
            padding: 8px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .return-button {
            position: fixed;
            bottom: 10px;
            left: 10px;
            font-size: 24px;
            cursor: pointer;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h2>Admin Login</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="submit" value="Login">
    </form>
    <a href="index.html" class="return-button">&#9664; Main menu</a>
</body>
</html>

<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the username and password match
    if ($_POST["username"] === "USERNAME" && $_POST["password"] === "PASSWORD") {
        // Set session variables
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = "saadak";
        // Redirect to the admin panel
        header("Location: blockchain.php");
        exit;
    } else {
        // Display error message
        echo "<p style='color: red;'>Invalid username or password.</p>";
    }
}
?>