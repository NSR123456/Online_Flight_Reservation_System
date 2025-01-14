<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['passport_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['passport_id'];
// Database credentials
$host = 'localhost';
$db = 'flight_reservation_system';
$user = 'panjas';
$password = 'Panjas@cse1';

// Establish database connection
$conn = new mysqli($host, $user, $password, $db);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $cab_reg_no = $_POST['cab_reg_no'];
    $pickup_location = $_POST['pickup_location'];
    $dropoff_location = $_POST['dropoff_location'];
    $price = $_POST['price'];

    // Insert data into CabBooking table
    $sql = "INSERT INTO BookCab (customer_id, cab_reg_no, pickup_location, dropoff_location, price) 
            VALUES ('$customer_id', '$cab_reg_no', '$pickup_location', '$dropoff_location', '$price')";

    if ($conn->query($sql) === TRUE) {
        echo "Cab booked successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!-- HTML Form -->
<form method="POST">
    
    Cab Registration No: <input type="text" name="cab_reg_no" required><br>
    Pickup Location: <input type="text" name="pickup_location" required><br>
    Dropoff Location: <input type="text" name="dropoff_location" required><br>
    Price: <input type="number" name="price" step="0.01" required><br>
    <button type="submit">Book Cab</button>
</form>
