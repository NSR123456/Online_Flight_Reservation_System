<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require 'logout.php';

// Ensure the user is logged in
if (!isset($_SESSION['passport_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user data from session
$passport_id = $_SESSION['passport_id'];
$name = $_SESSION['name'];
$dob = $_SESSION['dob'];
$nationality = $_SESSION['nationality'];

// Database connection
$servername = "localhost";
$username = "panjas"; // Update this if using a different username
$password = "Panjas@cse1"; // Update this if using a password
$dbname = "flight_reservation_system"; // Update with your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the customer's flight booking details
$flightQuery = "SELECT 
                    bf.flight_no, 
                    f.flight_name, 
                    fs.source, 
                    fs.destination, 
                    fs.departure_date, 
                    fs.departure_time, 
                    t.bill, 
                    a.airport_name AS airport
                FROM BookFlight bf
                JOIN Transactions t ON bf.transaction_id = t.id
                JOIN Flight_Schedule fs ON bf.schedule_id = fs.id
                JOIN Flights f ON fs.flight_no = f.flight_no
                JOIN Airports a ON bf.airport_id = a.id
                WHERE bf.customer_id = '$passport_id'
                ORDER BY fs.departure_date DESC, fs.departure_time DESC";

$flightResult = $conn->query($flightQuery);

// Fetch the customer's cab booking details
$cabQuery = "SELECT 
                 cb.cab_reg_no, 
                 c.driver_name, 
                 crp.price, 
                 crp.dropoff_location, 
                 cb.booking_date 
             FROM BookCab cb
             JOIN Cabs c ON cb.cab_reg_no = c.reg_no
             JOIN Cab_Route_Price crp ON cb.route_id = crp.id
             WHERE cb.customer_id = '$passport_id'
             ORDER BY cb.booking_date DESC";

$cabResult = $conn->query($cabQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-900">

    <div class="container mx-auto p-8">
        <div class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
            <h1 class="text-3xl font-semibold mb-4 text-center">Welcome, <?php echo htmlspecialchars($name); ?></h1>
            <div class="text-lg">
                <p class="mb-2"><strong>Passport ID:</strong> <?php echo htmlspecialchars($passport_id); ?></p>
                <p class="mb-2"><strong>Date of Birth:</strong> <?php echo htmlspecialchars($dob); ?></p>
                <p class="mb-2"><strong>Nationality:</strong> <?php echo htmlspecialchars($nationality); ?></p>
            </div>

            <!-- Flight Booking Details -->
            <h2 class="text-2xl font-semibold mt-8 mb-4">Flight Bookings</h2>
            <?php if ($flightResult && $flightResult->num_rows > 0): ?>
                <div class="space-y-4">
                    <?php while ($flight = $flightResult->fetch_assoc()): ?>
                        <div class="bg-blue-50 p-4 rounded-lg shadow-sm">
                            <p><strong>Flight:</strong> <?php echo htmlspecialchars($flight['flight_name']); ?> (<?php echo htmlspecialchars($flight['flight_no']); ?>)</p>
                            <p><strong>From:</strong> <?php echo htmlspecialchars($flight['source']); ?></p>
                            <p><strong>To:</strong> <?php echo htmlspecialchars($flight['destination']); ?></p>
                            <p><strong>Departure Date:</strong> <?php echo htmlspecialchars($flight['departure_date']); ?></p>
                            <p><strong>Departure Time:</strong> <?php echo htmlspecialchars($flight['departure_time']); ?></p>
                            <p><strong>Airport:</strong> <?php echo htmlspecialchars($flight['airport']); ?></p>
                            <p><strong>Amount Paid:</strong> $<?php echo htmlspecialchars($flight['bill']); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No flight bookings found.</p>
            <?php endif; ?>

            <!-- Cab Booking Details -->
            <h2 class="text-2xl font-semibold mt-8 mb-4">Cab Bookings</h2>
            <?php if ($cabResult && $cabResult->num_rows > 0): ?>
                <div class="space-y-4">
                    <?php while ($cab = $cabResult->fetch_assoc()): ?>
                        <div class="bg-green-50 p-4 rounded-lg shadow-sm">
                            <p><strong>Cab:</strong> <?php echo htmlspecialchars($cab['cab_reg_no']); ?></p>
                            <p><strong>Driver:</strong> <?php echo htmlspecialchars($cab['driver_name']); ?></p>
                            <p><strong>Dropoff Location:</strong> <?php echo htmlspecialchars($cab['dropoff_location']); ?></p>
                            <p><strong>Amount Paid:</strong> $<?php echo htmlspecialchars($cab['price']); ?></p>
                            <p><strong>Booking Date:</strong> <?php echo htmlspecialchars($cab['booking_date']); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No cab bookings found.</p>
            <?php endif; ?>

            <!-- Logout Button -->
            <div class="mt-8 text-center">
                <form method="POST" action="logout.php">
                    <button type="submit" name="logout" class="text-white bg-red-500 hover:bg-red-600 px-4 py-2 rounded">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
