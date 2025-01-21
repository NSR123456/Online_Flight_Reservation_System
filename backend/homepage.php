<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start the session to check login status
session_start();

// Database connection
$servername = "localhost";
$username = "panjas";
$password = "Panjas@cse1";
$dbname = "flight_reservation_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available flights where seats are available
$flightsQuery = "
    SELECT f.flight_no, f.flight_name, f.capacity, as1.available_seats, a.airline_name
    FROM Flights f
    JOIN Flight_Schedule fs ON f.flight_no = fs.flight_no
    JOIN AllocateSeat as1 ON fs.id = as1.schedule_id
    JOIN BelongTo b ON f.flight_no = b.flight_no
    JOIN Airlines a ON b.airline_id = a.airline_id
    WHERE as1.available_seats > 0
    LIMIT 5
";


$flightsResult = $conn->query($flightsQuery);

// Fetch Airlines Information
$airlinesQuery = "
    SELECT airline_id, airline_name, headquarter, operating_regions, contact 
    FROM Airlines LIMIT 5
";

$airlinesResult = $conn->query($airlinesQuery);

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['passport_id']);
$userName = 'Guest';  // Default to 'Guest'

// Fetch user name if logged in
if ($isLoggedIn) {
    $passport_id = $_SESSION['passport_id'];
    $userQuery = "SELECT name FROM Customers WHERE passport_id = ?";
    
    // Prepare and execute the query
    $stmt = $conn->prepare($userQuery);
    $stmt->bind_param("s", $passport_id);
    $stmt->execute();
    $stmt->bind_result($userName);
    $stmt->fetch();
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Flight Reservation System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Navbar -->
    <nav class="bg-blue-600 shadow-md fixed top-0 left-0 w-full z-10">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="index.php" class="text-white text-2xl font-bold">Flight Reservation</a>
                <div class="hidden md:flex space-x-8">
                    <a href="book_flight.php" class="text-white hover:text-blue-300">Book Flight</a>
                    <a href="book_cab.php" class="text-white hover:text-blue-300">Book Cab</a>
                    <a href="cancel_booking.php" class="text-white hover:text-blue-300">Cancel Booking</a>
                    <a href="customer_care.php" class="text-white hover:text-blue-300">Customer Care</a>
                    <a href="register.php" class="text-white hover:text-blue-300">Register</a>
                    <a href="login.php" class="text-white hover:text-blue-300">Login</a>
                    <a href="profile.php" class="text-white hover:text-blue-300">Profile</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-blue-500 text-white py-32">
        <div class="container mx-auto text-center">
            <?php if ($isLoggedIn): ?>
                <h2 class="text-5xl font-semibold mb-4">Hello, <?= htmlspecialchars($userName) ?>! You are logged in.</h2>
            <?php else: ?>
                <p class="text-lg mb-4">You are not logged in. <a href="login.php" class="text-yellow-400">Click here to log in</a>.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Flights and Airlines Information Section -->
    <section class="py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-semibold mb-8">Available Flights & Airlines</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-8">

                <!-- Available Flights Information Cards -->
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-2xl font-bold mb-4">Available Flights</h3>
                    <?php if ($flightsResult->num_rows > 0): ?>
                        <ul>
                            <?php while($flight = $flightsResult->fetch_assoc()): ?>
                                <li class="text-gray-700">
                                    <strong><?= htmlspecialchars($flight['flight_name']) ?> (Flight No: <?= htmlspecialchars($flight['flight_no']) ?>)</strong><br>
                                    Airline: <?= htmlspecialchars($flight['airline_name']) ?><br>
                                    Available Seats: <?= htmlspecialchars($flight['available_seats']) ?><br>
                                    Total Capacity: <?= htmlspecialchars($flight['capacity']) ?>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>No available flights at the moment.</p>
                    <?php endif; ?>
                </div>

                <!-- Airlines Information Cards -->
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-2xl font-bold mb-4">Airlines</h3>
                    <?php if ($airlinesResult->num_rows > 0): ?>
                        <ul>
                            <?php while($airline = $airlinesResult->fetch_assoc()): ?>
                                <li class="text-gray-700">
                                    <strong><?= htmlspecialchars($airline['airline_name']) ?> (ID: <?= htmlspecialchars($airline['airline_id']) ?>)</strong><br>
                                    Headquarter: <?= htmlspecialchars($airline['headquarter']) ?><br>
                                    Operating Regions: <?= htmlspecialchars($airline['operating_regions']) ?><br>
                                    Contact: <?= htmlspecialchars($airline['contact']) ?>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>No airline information available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white py-8">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 FlightRes. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
