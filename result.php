<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: rgb(150, 0, 150);
            color: white;
        }

        tr:nth-child(odd) {
            background-color: #f5f5f5;
        }
        tr:nth-child(even) {
            background-color: #ffffff;
        }
        .logout-link {
            text-align: center;
            margin-top: 20px;
        }
        .logout-link a {
            color: blue;
            text-decoration: underline;
        }

        .logout-link a:hover {
        color: purple; 
        cursor: pointer; 
    }
    </style>
</head>
<body>
    <h1>Vote Results</h1>
    
    <?php
    include '/u/b/e2203120/public_html/vote/config/db_config.php';

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve vote results from the database
    $sql_results = "SELECT block_id, user_id, data, timestamp FROM results";
    $result_results = mysqli_query($conn, $sql_results);

    if ($result_results && mysqli_num_rows($result_results) > 0) {
        echo '<table>';
        echo '<tr><th>Block ID</th><th>User ID</th><th>Team</th><th>Timestamp</th></tr>';
        while ($row = mysqli_fetch_assoc($result_results)) {
            echo '<tr>';
            echo '<td>' . $row['block_id'] . '</td>';
            echo '<td>' . $row['user_id'] . '</td>';
            echo '<td>' . $row['data'] . '</td>';
            echo '<td>' . $row['timestamp'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "No results found in the database.";
    }

    // Retrieve total vote count for each team
    $sql_total_votes = "SELECT data AS team, COUNT(*) AS votes FROM results GROUP BY data";
    $result_total_votes = mysqli_query($conn, $sql_total_votes);

    if ($result_total_votes && mysqli_num_rows($result_total_votes) > 0) {
        echo '<h2>Total Votes</h2>';
        echo '<table>';
        echo '<tr><th>Team</th><th>Votes</th></tr>';
        while ($row = mysqli_fetch_assoc($result_total_votes)) {
            echo '<tr>';
            echo '<td>' . $row['team'] . '</td>';
            echo '<td>' . $row['votes'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "No total votes found.";
    }

    mysqli_close($conn);
    ?>
    
    <div class="logout-link">
        <a href="index.html">Logout</a>
    </div>
</body>
</html>











