<?php
// test_db.php

$servername = "localhost"; // Database server
$username = "panjas"; // MySQL username (default is root)
$password = "Panjas@cse1"; // MySQL password (default is empty for root)
$dbname = "your_database_name"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully to the database!";
}

// Close connection
$conn->close();
?>
