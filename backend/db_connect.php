<?php
$host = 'localhost';
$db = 'flight_booking_system';
$user = 'panjas';
$password = 'Panjas@cse1';

// Establishing the connection
$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
