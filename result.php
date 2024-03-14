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
        table {
            width: 100%;
            border-collapse: collapse;
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

    // Connect to the database
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check the connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Truncate the results table before inserting new data to ensure a fresh start
    $sql_truncate = "TRUNCATE TABLE results";
    if (mysqli_query($conn, $sql_truncate)) {
    } else {
        echo "Error truncating results table: " . mysqli_error($conn);
    }

    // Initialize an array to store team IDs for which the names couldn't be retrieved
    $unknown_teams = array();

    // Retrieve vote counts for each team from the votes table
    $sql_votes = "SELECT team_id, COUNT(*) AS total_votes FROM votes GROUP BY team_id";
    $result_votes = mysqli_query($conn, $sql_votes);

    // Check if the query was successful
    if ($result_votes) {
        // Insert vote counts into the results table
        while ($row = mysqli_fetch_assoc($result_votes)) {
            $team_id = $row['team_id'];
            $total_votes = $row['total_votes'];

            // Retrieve the team name corresponding to the team_id from the votes table
            $sql_team_name = "SELECT team_name FROM votes WHERE team_id = '$team_id' LIMIT 1";
            $result_team_name = mysqli_query($conn, $sql_team_name);

            // Check if the query was successful and if a team name was found
            if ($result_team_name) {
                if (mysqli_num_rows($result_team_name) > 0) {
                    $row_team_name = mysqli_fetch_assoc($result_team_name);
                    $team_name = $row_team_name['team_name'];
                } else {
                    $team_name = 'Unknown';
                    $unknown_teams[] = $team_id; // Store the unknown team ID
                }
            } else {
                echo "Error retrieving team name for team ID '$team_id': " . mysqli_error($conn) . ". ";
            }

            // Insert the vote count into the results table
            $sql_insert = "INSERT INTO results (team_id, team_name, total_votes) VALUES ('$team_id', '$team_name', '$total_votes')";
            if (mysqli_query($conn, $sql_insert)) {
                // Don't output the message here
            } else {
                echo "Error inserting record: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Error retrieving vote counts: " . mysqli_error($conn);
    }

    // Output unknown team IDs
    if (!empty($unknown_teams)) {
        echo "Error retrieving team names for team ID(s): " . implode(', ', $unknown_teams) . ". ";
    }

   

    // Retrieve all teams and their corresponding vote counts
    $sql_results = "SELECT v.team_name, COALESCE(r.total_votes, 0) AS total_votes 
    FROM (SELECT DISTINCT team_name FROM votes) AS v
    LEFT JOIN results AS r ON v.team_name = r.team_name";
    $result_results = mysqli_query($conn, $sql_results);

    // Check if any rows are fetched
    if ($result_results && mysqli_num_rows($result_results) > 0) {
        // Output vote counts for each team in a table
        echo '<table>';
        echo '<tr><th>Team Name</th><th>Votes</th></tr>';
        while ($row = mysqli_fetch_assoc($result_results)) {
            echo '<tr>';
            echo '<td>' . $row['team_name'] . '</td>';
            echo '<td>' . $row['total_votes'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "No results found in the results table.";
    }

    // Close the database connection
    mysqli_close($conn);
    ?>
    
    <div class="logout-link">
        <a href="index.html">Logout</a>
    </div>
</body>
</html>











