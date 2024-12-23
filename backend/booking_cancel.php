<?php
$servername = "localhost";
$username = "panjas";
$password = "Panjas@cse1";
$dbname = "flight_reservation_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the necessary inputs to uniquely identify the booking
    $customer_id = $_POST['customer_id'];
    $flight_no = $_POST['flight_no'];
    $airport_id = $_POST['airport_id'];

    // First, check if the booking exists
    $sql_check = "SELECT * FROM FlightBooking WHERE customer_id = '$customer_id' AND flight_no = '$flight_no' AND airport_id = '$airport_id'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        // Delete the booking
        $sql_cancel = "DELETE FROM FlightBooking WHERE customer_id = '$customer_id' AND flight_no = '$flight_no' AND airport_id = '$airport_id'";
        if ($conn->query($sql_cancel) === TRUE) {
            echo "Booking cancelled successfully!";
        } else {
            echo "Error cancelling booking: " . $conn->error;
        }
    } else {
        echo "Booking not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Booking</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

    <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4">Cancel Booking</h1>

        <form method="POST">
            <div class="mb-4">
                <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer ID</label>
                <input type="text" name="customer_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>

            <div class="mb-4">
                <label for="flight_no" class="block text-sm font-medium text-gray-700">Flight No</label>
                <input type="text" name="flight_no" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>

            <div class="mb-4">
                <label for="airport_id" class="block text-sm font-medium text-gray-700">Airport ID</label>
                <input type="text" name="airport_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Cancel Booking</button>
        </form>
    </div>

</body>
</html>
