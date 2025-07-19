<?php
$server = "localhost";
$user = "root";
$pass = "";
$dbname = "blogpostdb";

// Create connection
$conn = new mysqli($server, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
} 
?>
