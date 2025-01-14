<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "panjas";
$password = "Panjas@cse1";
$dbname = "flight_reservation_system";

// Establish a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $from_location = mysqli_real_escape_string($conn, $_POST['from_location']);
    $to_location = mysqli_real_escape_string($conn, $_POST['to_location']);
    $departure_date = mysqli_real_escape_string($conn, $_POST['departure_date']);

    // Prepare SQL query to get flights based on search
    $sql = "SELECT fs.*, f.flight_name, f.flight_no 
            FROM Flight_Schedule fs
            JOIN Flights f ON fs.flight_no = f.flight_no
            WHERE fs.from_location = '$from_location' 
              AND fs.to_location = '$to_location'
              AND fs.departure_date = '$departure_date'";

    if ($result = $conn->query($sql)) {
        if ($result->num_rows > 0) {
            echo "<table class='min-w-full border-collapse border border-gray-300'><tr class='bg-gray-200'><th class='px-4 py-2 border'>Flight Name</th><th class='px-4 py-2 border'>Departure Date</th><th class='px-4 py-2 border'>Flight No</th><th class='px-4 py-2 border'>Available Seats</th><th class='px-4 py-2 border'>Action</th></tr>";
            while ($row = $result->fetch_assoc()) {
                // Get available seats for each flight
                $schedule_id = $row['id'];
                $seat_check_query = "SELECT available_seats 
                                     FROM SeatAllocating 
                                     WHERE schedule_id = '$schedule_id' AND status = 'Available'";
                $seat_check_result = $conn->query($seat_check_query);
                $available_seats = $seat_check_result->num_rows;

                echo "<tr class='border-b'><td class='px-4 py-2'>{$row['flight_name']}</td><td class='px-4 py-2'>{$row['departure_date']}</td><td class='px-4 py-2'>{$row['flight_no']}</td><td class='px-4 py-2'>$available_seats</td>";
                
                if ($available_seats > 0) {
                    echo "<td class='px-4 py-2 text-blue-500'><a href='book_flight.php?schedule_id={$row['id']}'>Book Now</a></td>";
                } else {
                    echo "<td class='px-4 py-2 text-red-500'>Sold Out</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No flights available.</p>";
        }
        $result->free();
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Reservation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-6">

    <h1 class="text-3xl font-semibold mb-6">Search Flights</h1>

    <form method="POST" class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
        <label for="from_location" class="block mb-2 font-medium">From:</label>
        <input type="text" id="from_location" name="from_location" required class="w-full p-2 border border-gray-300 rounded-lg mb-4">

        <label for="to_location" class="block mb-2 font-medium">To:</label>
        <input type="text" id="to_location" name="to_location" required class="w-full p-2 border border-gray-300 rounded-lg mb-4">

        <label for="departure_date" class="block mb-2 font-medium">Departure Date:</label>
        <input type="date" id="departure_date" name="departure_date" required class="w-full p-2 border border-gray-300 rounded-lg mb-4">

        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">Search</button>
    </form>

</body>
</html>
