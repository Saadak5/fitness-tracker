<?php
include '/u/b/e2203120/public_html/vote/config/db_config.php';

session_start();

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Process form submission to add a vote to the blockchain
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['team'])) {
        $team = $_POST['team'];

        // Check if user is logged in and user_id is set in the session
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            
            // Get the current timestamp
            $timestamp = date("Y-m-d H:i:s");

            // Get the previous hash from the last block
            $previous_hash_sql = "SELECT current_hash FROM results ORDER BY block_id DESC LIMIT 1";
            $previous_hash_result = $conn->query($previous_hash_sql);
            $previous_hash_row = $previous_hash_result->fetch_assoc();
            $previous_hash = ($previous_hash_row) ? $previous_hash_row['current_hash'] : "0 (Genesis Block)"; // Set to "0" for the genesis block if no previous block exists

            // Calculate hashed data
            $data = $team;
            $hashed_data = hash('sha256', $data); // Example hashing algorithm (use appropriate hashing algorithm)

            // Calculate current_hash
            $current_hash = hash('sha256', $timestamp . $previous_hash . $team . $data . $hashed_data); // Calculate the hash based on other fields
            
            // Get the last blockchain index
            $last_index_sql = "SELECT MAX(block_id) as max_index FROM results";
            $last_index_result = $conn->query($last_index_sql);
            $last_index_row = $last_index_result->fetch_assoc();
            $current_index = $last_index_row['max_index'] + 1;

            // Add the vote to the results table
            $sql = "INSERT INTO results (block_id, user_id, timestamp, previous_hash, data, hashed_data, current_hash) VALUES ('$current_index', '$user_id', '$timestamp', '$previous_hash', '$data', '$hashed_data', '$current_hash')";
            if (mysqli_query($conn, $sql)) {
                // Redirect the user to result.php after successful vote insertion
                header("Location: result.php");
                exit(); // Ensure script execution stops after redirection
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            echo "User session not found. Please log in.<br>";
        }
    } else {
        echo "Team selection is required.<br>";
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Team Voting - Vote</title>
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
            background-color: rgb(150, 0, 150);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background-color: rgb(236, 45, 236);
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: rgb(235, 102, 235);
        }
        .logout-link {
            text-align: center;
            margin-top: 20px;
        }
        .logout-link a:hover {
            color: black;
        }
        .logout-link a {
            color: white;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Vote for Your Preferred Football Team</h2>
        <form action="vote.php" method="post">
            <label for="team">Select Your Team:</label>
            <select id="team" name="team">
                <option value="Arsenal">Arsenal</option>
                <option value="Manchester City">Manchester City</option>
                <option value="Liverpool">Liverpool</option>
                <option value="Manchester United">Manchester United</option>
                <option value="Chelsea">Chelsea</option>
                <option value="Real Madrid">Real Madrid</option>
                <option value="Bayer Munchen">Bayer Munchen</option>
                <option value="Barcelona">Barcelona</option>
                <option value="Ac Milan">Ac Milan</option>
                <option value="PSG">PSG</option>
            </select>
            <input type="submit" value="Submit Vote">
        </form>
        <div class="logout-link">
            <a href="login.php">Logout</a>
        </div>
    </div>
</body>
</html>