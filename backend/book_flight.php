<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$db = 'flight_reservation_system';
$user = 'panjas';
$password = 'Panjas@cse1';

// Establish a database connection
$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available flights
$flights_query = "SELECT flight_no, flight_name FROM Flights";
$flights_result = $conn->query($flights_query);

// Fetch available airports
$airports_query = "SELECT id, airport_name FROM Airports";
$airports_result = $conn->query($airports_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $flight_no = $_POST['flight_no'];
    $bill = $_POST['bill'];
    $airport_id = $_POST['airport_id'];

    // Check available seats before booking
    $seats_check_query = "SELECT no_of_seat, 
                                (no_of_seat - COALESCE((SELECT SUM(no_of_seat) FROM FlightBooking WHERE flight_no = '$flight_no'), 0)) AS available_seats
                           FROM Flights 
                           WHERE flight_no = '$flight_no'";

    $result = $conn->query($seats_check_query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $available_seats = $row['available_seats'];

        if ($available_seats > 0) {
            // If there are available seats, proceed with booking
            $sql = "INSERT INTO FlightBooking (customer_id, flight_no, bill, airport_id) 
                    VALUES ('$customer_id', '$flight_no', '$bill', '$airport_id')";

            if ($conn->query($sql) === TRUE) {
                echo "<p class='text-green-600 font-semibold'>Booking successful! Available seats: $available_seats</p>";
            } else {
                echo "<p class='text-red-600 font-semibold'>Error: " . $conn->error . "</p>";
            }
        } else {
            echo "<p class='text-red-600 font-semibold'>No available seats for the selected flight.</p>";
        }
    } else {
        echo "<p class='text-red-600 font-semibold'>Flight not found.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Flight</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-900">

    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-semibold mb-6 text-center">Flight Booking</h1>

        <form method="POST" class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
            <div class="mb-4">
                <label for="customer_id" class="block text-lg font-semibold text-gray-700">Customer ID:</label>
                <input type="text" name="customer_id" id="customer_id" required class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="flight_no" class="block text-lg font-semibold text-gray-700">Flight No:</label>
                <select name="flight_no" id="flight_no" required class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Flight</option>
                    <?php
                    if ($flights_result->num_rows > 0) {
                        while ($row = $flights_result->fetch_assoc()) {
                            echo "<option value='" . $row['flight_no'] . "'>" . $row['flight_name'] . " (" . $row['flight_no'] . ")</option>";
                        }
                    } else {
                        echo "<option value=''>No flights available</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="bill" class="block text-lg font-semibold text-gray-700">Bill Amount:</label>
                <input type="number" name="bill" id="bill" step="0.01" required class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="airport_id" class="block text-lg font-semibold text-gray-700">Airport:</label>
                <select name="airport_id" id="airport_id" required class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Airport</option>
                    <?php
                    if ($airports_result->num_rows > 0) {
                        while ($row = $airports_result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['airport_name'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No airports available</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Book Flight</button>
        </form>
    </div>
</body>
</html>
