<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

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

// Fetch the customer's booking details
$flightQuery = "SELECT fb.flight_no, f.flight_name, fb.bill, fs.from_location, fs.to_location, fs.departure_date
                FROM FlightBooking fb
                JOIN Flights f ON fb.flight_no = f.flight_no
                JOIN Flight_Schedule fs ON fs.flight_no = fb.flight_no
                WHERE fb.customer_id = '$passport_id'";


$cabQuery = "SELECT cb.cab_reg_no, c.driver_name, cb.price, cb.pickup_location, cb.dropoff_location
             FROM CabBooking cb
             JOIN Cabs c ON cb.cab_reg_no = c.reg_no
             WHERE cb.customer_id = '$passport_id'";

// Execute queries
$flightResult = $conn->query($flightQuery);
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
            <h1 class="text-3xl font-semibold mb-4 text-center">Welcome, <?php echo $name; ?></h1>
            <div class="text-lg">
                <p class="mb-2"><strong>Passport ID:</strong> <?php echo $passport_id; ?></p>
                <p class="mb-2"><strong>Date of Birth:</strong> <?php echo $dob; ?></p>
                <p class="mb-2"><strong>Nationality:</strong> <?php echo $nationality; ?></p>
            </div>

            <!-- Flight Booking Details -->
            <h2 class="text-2xl font-semibold mt-8 mb-4">Flight Bookings</h2>
            <?php if ($flightResult->num_rows > 0): ?>
                <div class="space-y-4">
                    <?php while ($flight = $flightResult->fetch_assoc()): ?>
                        <div class="bg-blue-50 p-4 rounded-lg shadow-sm">
                            <p><strong>Flight:</strong> <?php echo $flight['flight_name']; ?> (<?php echo $flight['flight_no']; ?>)</p>
                            <p><strong>From:</strong> <?php echo $flight['from_location']; ?></p>
                            <p><strong>To:</strong> <?php echo $flight['to_location']; ?></p>
                            <p><strong>Departure Date:</strong> <?php echo $flight['departure_date']; ?></p>
                            <p><strong>Amount Paid:</strong> $<?php echo $flight['bill']; ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No flight bookings found.</p>
            <?php endif; ?>

            <!-- Cab Booking Details -->
            <h2 class="text-2xl font-semibold mt-8 mb-4">Cab Bookings</h2>
            <?php if ($cabResult->num_rows > 0): ?>
                <div class="space-y-4">
                    <?php while ($cab = $cabResult->fetch_assoc()): ?>
                        <div class="bg-green-50 p-4 rounded-lg shadow-sm">
                            <p><strong>Cab:</strong> <?php echo $cab['cab_reg_no']; ?></p>
                            <p><strong>Driver:</strong> <?php echo $cab['driver_name']; ?></p>
                            <p><strong>Pickup Location:</strong> <?php echo $cab['pickup_location']; ?></p>
                            <p><strong>Dropoff Location:</strong> <?php echo $cab['dropoff_location']; ?></p>
                            <p><strong>Amount Paid:</strong> $<?php echo $cab['price']; ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No cab bookings found.</p>
            <?php endif; ?>

        </div>
    </div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
