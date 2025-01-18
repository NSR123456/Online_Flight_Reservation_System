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
$servername = "localhost";
$username = "panjas";
$password = "Panjas@cse1";
$dbname = "flight_reservation_system";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle cab booking cancellation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_cab'])) {
    $cab_booking_id = $_POST['cab_reg_no']; // Retrieve cab reg no

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Restore cab booking to previous state
        $update_cab_query = "delete from BookCab WHERE id = '$cab_booking_id' AND customer_id = '$customer_id' limit 1";
        if (!$conn->query($update_cab_query)) {
            throw new Exception("Error restoring cab booking: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();
        echo "<p class='text-green-600 font-semibold'>Cab booking cancelled successfully!</p>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<p class='text-red-600 font-semibold'>Error: " . $e->getMessage() . "</p>";
    }
}

// Handle flight booking actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['manage_flight'])) {
    $flight_no = $_POST['flight_no'];

    // Example: Cancel flight booking
    $cancel_flight_query = "DELETE FROM BookFlight WHERE flight_no = '$flight_no' AND customer_id = '$customer_id' limit 1";
    if ($conn->query($cancel_flight_query)) {
        echo "<p class='text-green-600 font-semibold'>Flight booking cancelled successfully!</p>";
    } else {
        echo "<p class='text-red-600 font-semibold'>Error cancelling flight: " . $conn->error . "</p>";
    }
}

// Fetch upcoming cab bookings
$cabs_query = "SELECT cab.reg_no, cp.pickup_location, cp.dropoff_location  FROM BookCab bc 
                join Cab_Route_Price cp on bc.route_id = cp.id
                join Cabs cab on cab.reg_no = bc.cab_reg_no
WHERE customer_id = '$customer_id'";
$cabs_result = $conn->query($cabs_query);

// Fetch upcoming flight bookings
$flights_query = "SELECT b.flight_no, a.airport_name, b.schedule_id, sch.departure_date, sch.departure_time
                FROM BookFlight b 
                join Flights f on b.flight_no = f.flight_no
                join Flight_Schedule sch on sch.id = b.schedule_id
                join Airports a on a.id = b.airport_id
                WHERE customer_id = '$customer_id' AND departure_time > NOW()";
$flights_result = $conn->query($flights_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, select, button {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Booking Management</h1>

    <!-- Cab Booking Cancellation Form -->
    <h2>Cancel Cab Booking</h2>
    <form method="POST">
        <label for="cab_reg_no">Select Cab Booking:</label>
        <select name="cab_reg_no" id="cab_reg_no" required>
            <?php if ($cabs_result->num_rows > 0): ?>
                <?php while ($row = $cabs_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['cab_reg_no']; ?>">
    <?php echo "Cab: " . $row['cab_reg_no'] . " (Pickup: " . $row['pickup_location'] . ", Dropoff: " . $row['dropoff_location'] . ")"; ?>
</option>


                <?php endwhile; ?>
            <?php else: ?>
                <option value="" disabled>No cab bookings available</option>
            <?php endif; ?>
        </select>
        <button type="submit" name="cancel_cab">Cancel Cab Booking</button>
    </form>

    <!-- Flight Booking Management Form -->
    <h2>Cancel Flight Booking</h2>
    <form method="POST">
        <label for="flight_no">Select Flight Booking:</label>
        <select name="flight_no" id="flight_no" required>
            <?php if ($flights_result->num_rows > 0): ?>
                <?php while ($row = $flights_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['flight_no'] . ',' . $row['schedule_id']; ?>">
                        <?php echo "Flight: " . $row['flight_no'] . ", Airport: " . $row['airport_name'] . ", Schedule: " . $row['schedule_id'] . " (Departure: " . $row['departure_date'] . " " . $row['departure_time'] . ")"; ?>
                    </option>

                <?php endwhile; ?>
            <?php else: ?>
                <option value="" disabled>No flight bookings available</option>
            <?php endif; ?>
        </select>
        <button type="submit" name="manage_flight">Cancel Flight Booking</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
