<?php
include 'config/db_config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $username = $_POST['username'];
    $password = $_POST['password'];

 
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

   
    $conn = new mysqli($servername, $username, $password, $dbname);

   
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

  
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

 
    if ($stmt->execute()) {
        header("Location: login.html");
        exit();
    } else {
        echo "Error registering user: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>