<?php
// db_connection.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ec"; // Use your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
