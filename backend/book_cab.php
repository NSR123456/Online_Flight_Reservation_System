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

// Fetch the airports associated with the logged-in user's bookings
$sql_airports = "
    SELECT DISTINCT a.id, a.airport_name 
    FROM BookFlight bf
    JOIN Airports a ON bf.airport_id = a.id
    WHERE bf.customer_id = '$customer_id'
";

$result_airports = $conn->query($sql_airports);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cab_reg_no = $_POST['cab_reg_no'];
    $from_airport_id = $_POST['from_airport_id']; // Selected airport ID
    $dropoff_location = $_POST['dropoff_location'];
    $booking_date = $_POST['booking_date'];

    // Fetch the price based on the selected airport and dropoff location
    $sql_price = "SELECT price FROM Cab_Route_Price WHERE dropoff_location = '$dropoff_location'";
    $result_price = $conn->query($sql_price);

    if ($result_price->num_rows > 0) {
        $row = $result_price->fetch_assoc();
        $price = $row['price'];

        // Retrieve the route_id for the selected dropoff location
        $sql_route_id = "SELECT id FROM Cab_Route_Price WHERE dropoff_location = '$dropoff_location'";
        $result_route_id = $conn->query($sql_route_id);

        if ($result_route_id->num_rows > 0) {
            $row = $result_route_id->fetch_assoc();
            $route_id = $row['id'];

            // Insert into BookCab table with the retrieved route_id and user inputs
            $sql_insert = "INSERT INTO BookCab (route_id, cab_reg_no, customer_id, from_airport_id, booking_date) 
                           VALUES ('$route_id', '$cab_reg_no', '$customer_id', '$from_airport_id', '$booking_date')";
            if ($conn->query($sql_insert) === TRUE) {
                echo "Cab booked successfully! Price: " . $price;
            } else {
                echo "Error: " . $sql_insert . "<br>" . $conn->error;
            }
        } else {
            echo "Route not found!";
        }
    } else {
        echo "Price not found for this route!";
    }
}
?>

<!-- HTML Form -->
<form method="POST">
    <!-- Cab Registration No -->
    Cab Registration No: 
    <select name="cab_reg_no" required>
        <?php
        $sql_cabs = "SELECT reg_no FROM Cabs";
        $result_cabs = $conn->query($sql_cabs);
        if ($result_cabs->num_rows > 0) {
            while ($row = $result_cabs->fetch_assoc()) {
                echo "<option value='" . $row['reg_no'] . "'>" . $row['reg_no'] . "</option>";
            }
        }
        ?>
    </select><br>

    <!-- Airport Selection (Dropdown based on user's bookings) -->
    Pickup Airport: 
    <select name="from_airport_id" required>
        <?php
        if ($result_airports->num_rows > 0) {
            while ($row = $result_airports->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['airport_name']) . "</option>";
            }
        } else {
            echo "<option value=''>No airports found</option>";
        }
        ?>
    </select><br>

    <!-- Dropoff Location -->
    Dropoff Location: 
    <select name="dropoff_location" required>
        <?php
        $sql_routes = "SELECT DISTINCT dropoff_location FROM Cab_Route_Price";
        $result_routes = $conn->query($sql_routes);
        if ($result_routes->num_rows > 0) {
            while ($row = $result_routes->fetch_assoc()) {
                echo "<option value='" . $row['dropoff_location'] . "'>" . $row['dropoff_location'] . "</option>";
            }
        }
        ?>
    </select><br>

    <!-- Booking Date -->
    Booking Date:
    <input type="date" name="booking_date" required><br>

    <button type="submit">Book Cab</button>
</form>
