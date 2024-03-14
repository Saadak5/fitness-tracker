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

   
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    
    $sql_truncate = "TRUNCATE TABLE results";
    if (mysqli_query($conn, $sql_truncate)) {
    } else {
        echo "Error truncating results table: " . mysqli_error($conn);
    }

    
    $unknown_teams = array();

    
    $sql_votes = "SELECT team_id, COUNT(*) AS total_votes FROM votes GROUP BY team_id";
    $result_votes = mysqli_query($conn, $sql_votes);

    
    if ($result_votes) {
        
        while ($row = mysqli_fetch_assoc($result_votes)) {
            $team_id = $row['team_id'];
            $total_votes = $row['total_votes'];

            
            $sql_team_name = "SELECT team_name FROM votes WHERE team_id = '$team_id' LIMIT 1";
            $result_team_name = mysqli_query($conn, $sql_team_name);

            
            if ($result_team_name) {
                if (mysqli_num_rows($result_team_name) > 0) {
                    $row_team_name = mysqli_fetch_assoc($result_team_name);
                    $team_name = $row_team_name['team_name'];
                } else {
                    $team_name = 'Unknown';
                    $unknown_teams[] = $team_id; 
                }
            } else {
                echo "Error retrieving team name for team ID '$team_id': " . mysqli_error($conn) . ". ";
            }

            
            $sql_insert = "INSERT INTO results (team_id, team_name, total_votes) VALUES ('$team_id', '$team_name', '$total_votes')";
            if (mysqli_query($conn, $sql_insert)) {
                
            } else {
                echo "Error inserting record: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Error retrieving vote counts: " . mysqli_error($conn);
    }

    
    if (!empty($unknown_teams)) {
        echo "Error retrieving team names for team ID(s): " . implode(', ', $unknown_teams) . ". ";
    }

   

    
    $sql_results = "SELECT v.team_name, COALESCE(r.total_votes, 0) AS total_votes 
    FROM (SELECT DISTINCT team_name FROM votes) AS v
    LEFT JOIN results AS r ON v.team_name = r.team_name";
    $result_results = mysqli_query($conn, $sql_results);

    
    if ($result_results && mysqli_num_rows($result_results) > 0) {
        
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


    mysqli_close($conn);
    ?>
    
    <div class="logout-link">
        <a href="index.html">Logout</a>
    </div>
</body>
</html>











