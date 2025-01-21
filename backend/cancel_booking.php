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
    $cab_booking_id = $_POST['cab_reg_no'];

    $conn->begin_transaction();

    try {
        $delete_cab_query = "DELETE FROM BookCab WHERE cab_reg_no = ? AND customer_id = ? LIMIT 1";
        $stmt = $conn->prepare($delete_cab_query);
        $stmt->bind_param("ss", $cab_booking_id, $customer_id);
        if (!$stmt->execute()) {
            throw new Exception("Error canceling cab booking: " . $stmt->error);
        }

        $conn->commit();
        echo "<p class='text-green-600 font-semibold'>Cab booking canceled successfully!</p>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<p class='text-red-600 font-semibold'>Error: " . $e->getMessage() . "</p>";
    }
}

// Handle flight booking cancellation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['manage_flight'])) {
    $flight_data = explode(',', $_POST['flight_no']); // Flight number and schedule ID
    $flight_no = $flight_data[0];
    $schedule_id = $flight_data[1];

    $delete_flight_query = "DELETE FROM BookFlight WHERE flight_no = ? AND schedule_id = ? AND customer_id = ? LIMIT 1";
    $stmt = $conn->prepare($delete_flight_query);
    $stmt->bind_param("sss", $flight_no, $schedule_id, $customer_id);
    if ($stmt->execute()) {
        echo "<p class='text-green-600 font-semibold'>Flight booking canceled successfully!</p>";
    } else {
        echo "<p class='text-red-600 font-semibold'>Error canceling flight: " . $stmt->error . "</p>";
    }
}

// Fetch upcoming cab bookings
// Fetch upcoming cab bookings
$cabs_query = "SELECT cab.reg_no, a1.airport_name AS pickup_airport, cp.dropoff_location, bc.booking_date
               FROM BookCab bc
               JOIN Cab_Route_Price cp ON bc.route_id = cp.id
               JOIN Cabs cab ON cab.reg_no = bc.cab_reg_no
               JOIN Airports a1 ON a1.id = bc.from_airport_id
               WHERE bc.customer_id = ?";
$stmt = $conn->prepare($cabs_query);
$stmt->bind_param("s", $customer_id);
$stmt->execute();
$cabs_result = $stmt->get_result();


// Fetch upcoming flight bookings
$flights_query = "SELECT b.flight_no, a.airport_name, b.schedule_id, sch.departure_date, sch.departure_time
                  FROM BookFlight b
                  JOIN Flights f ON b.flight_no = f.flight_no
                  JOIN Flight_Schedule sch ON sch.id = b.schedule_id
                  JOIN Airports a ON a.id = b.airport_id
                  WHERE b.customer_id = ? AND CONCAT(sch.departure_date, ' ', sch.departure_time) > NOW()";
$stmt = $conn->prepare($flights_query);
$stmt->bind_param("s", $customer_id);
$stmt->execute();
$flights_result = $stmt->get_result();
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
        select, button {
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
                    <option value="<?php echo $row['reg_no']; ?>">
                        <?php echo "Cab: " . $row['reg_no'] . ", Dropoff: " . $row['dropoff_location'] . ", Date: " . $row['booking_date'] . ")"; ?>
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
