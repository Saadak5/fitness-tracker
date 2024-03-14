<?php
include '/u/b/e2203120/public_html/vote/config/db_config.php';

$message = '';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION['user_id'] = $user_id;
            header("Location: vote.php");
            exit();
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "User not found.";
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Team Voting</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            color: white;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            background: rgb(150, 0, 150);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

    .container a {
        color: rgb(231, 158, 255);
    }
    .container a:hover{
        color: rgb(244, 211, 255);
    }
        h2 {
            text-align: center;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: rgb(236, 45, 236);
            color: #fff;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: rgb(235, 102, 235);
        }
        .return-button {
            position: fixed;
            bottom: 10px;
            left: 10px;
            font-size: 24px;
            cursor: pointer;
            text-decoration: none;
        }
        .error-message {
            color: red;
            margin-top: 10px;
            text-align: center;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>User Login</h2>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
        <?php if (!empty($message)) : ?>
            <div class="error-message"><?php echo $message; ?></div>
        <?php endif; ?>
        <p>Don't have an account yet? <a href="registeration.php">Register here</a>.</p>
    </div>
    <a href="index.html" class="return-button">&#9664; Main menu</a>
</body>
</html>
