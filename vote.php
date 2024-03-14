<?php
include '/u/b/e2203120/public_html/vote/config/db_config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $team_name = $_POST['team']; // Retrieve the selected team name from the form

    // Mapping of team names to IDs
    $team_id_map = [
        'Arsenal' => 1,
        'Manchester City' => 2,
        'Liverpool' => 3,
        'Manchester United' => 4,
        'Chelsea' => 5,
        'Real Madrid' => 6,
        'Bayer Munchen' => 7,
        'Barcelona' => 8,
        'Ac Milan' => 9,
        'PSG' => 10
    ];

    // Check if the selected team name exists in the mapping
    if (array_key_exists($team_name, $team_id_map)) {
        $team_id = $team_id_map[$team_name]; // Get the corresponding team ID
    } else {
        echo "Error: Invalid team selection.";
        exit();
    }

    $user_id = $_SESSION['user_id'];

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $stmt = $conn->prepare("INSERT INTO votes (user_id, team_id, team_name, timestamp) VALUES (?, ?, ?, CURRENT_TIMESTAMP)");
    $stmt->bind_param("iis", $user_id, $team_id, $team_name);


    if ($stmt->execute()) {
        header("Location: result.php");
        exit();
    } else {
        echo "Error submitting vote: " . $stmt->error;
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



