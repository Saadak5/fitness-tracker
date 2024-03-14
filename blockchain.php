<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: blockchain.php");
    exit;
}

include '/u/b/e2203120/public_html/vote/config/db_config.php';

// Connect to the database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve blockchain information from the blocks table
$sql_blocks = "SELECT * FROM blocks";
$result_blocks = mysqli_query($conn, $sql_blocks);

// Retrieve transaction information from the transactions table
$sql_transactions = "SELECT * FROM transactions";
$result_transactions = mysqli_query($conn, $sql_transactions);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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
    </style>
</head>
<body>
    <h1>Blockchain Information</h1>
    <?php 
    
    if (mysqli_num_rows($result_blocks) > 0) { ?>
        <h2>Blocks</h2>
        <table>
            <tr>
                <th>Block ID</th>
                <th>Timestamp</th>
                <th>Previous Hash</th>
                <th>Current Hash</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result_blocks)) { ?>
                <tr>
                    <td><?php echo $row['block_id']; ?></td>
                    <td><?php echo $row['timestamp']; ?></td>
                    <td><?php echo $row['previous_hash']; ?></td>
                    <td><?php echo $row['current_hash']; ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No blocks found.</p>
    <?php } ?>

    <?php if (mysqli_num_rows($result_transactions) > 0) { ?>
        <h2>Transactions</h2>
        <table>
            <tr>
                <th>Transaction ID</th>
                <th>Block ID</th>
                <th>User ID</th>
                <th>Team ID</th>
                <th>Timestamp</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result_transactions)) { ?>
                <tr>
                    <td><?php echo $row['transaction_id']; ?></td>
                    <td><?php echo $row['block_id']; ?></td>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo $row['team_id']; ?></td>
                    <td><?php echo $row['timestamp']; ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No transactions found.</p>
    <?php } ?>
    
    <br>
    <a href="admin.php">Logout</a>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>


