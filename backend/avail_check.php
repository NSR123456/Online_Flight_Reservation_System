<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$db = 'flight_reservation_system';
$user = 'panjas';
$password = 'Panjas@cse1';

// Establish a database connection
$conn = new mysqli($host, $user, $password, $db);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flight_no = $conn->real_escape_string($_POST['flight_no']); // Escape input for SQL

    // SQL query to calculate available seats
    $sql = "SELECT no_of_seat, 
                (no_of_seat - COALESCE((SELECT SUM(no_of_seat) FROM Transactions WHERE flight_no = '$flight_no'), 0)) AS available_seats
            FROM Flights 
            WHERE flight_no = '$flight_no'";

    $result = $conn->query($sql);

    // Check if the flight exists in the database
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<p class='text-green-600 font-semibold'>Available Seats: " . $row['available_seats'] . "</p>";
    } else {
        echo "<p class='text-red-600 font-semibold'>No data available for the flight.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Seat Availability</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-900">

    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-semibold mb-6 text-center">Flight Seat Availability</h1>

        <form method="POST" class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
            <div class="mb-4">
                <label for="flight_no" class="block text-lg font-semibold text-gray-700">Flight No:</label>
                <input type="text" name="flight_no" id="flight_no" required class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Check Availability</button>
        </form>
    </div>
</body>
</html>
