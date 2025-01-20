<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['passport_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['passport_id'];

$servername = "localhost";
$username = "panjas";
$password = "Panjas@cse1";
$dbname = "flight_reservation_system";

// Establish a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to log errors to a file
function log_error($message) {
    error_log($message, 3, 'error_log.txt');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['schedule_id'])) {
    
    $schedule_id = $_POST['schedule_id'];
    $seat_type = $_POST['seat_type'];
    $airport_id = $_POST['airport_id'];
    $ordered_seat = $_POST['ordered_seat'];

    // Calculate bill dynamically based on seat type
    $bill_query = "SELECT 
                (CASE 
                    WHEN '$seat_type' = 'Economy' THEN 100 
                    WHEN '$seat_type' = 'Business' THEN 200 
                    ELSE 300 
                END * $ordered_seat) AS seat_price,
                50 AS airport_fee 
                FROM DUAL";

    $bill_result = $conn->query($bill_query);
    if (!$bill_result) {
        die("Error in bill query: " . $conn->error);
    }
    $bill_data = $bill_result->fetch_assoc();
    $bill = $bill_data['seat_price'] + $bill_data['airport_fee'];

    // Check seat availability (exclude past schedules)
    $seat_check_query = "SELECT available_seats, status 
                         FROM AllocateSeat 
                         WHERE schedule_id = '$schedule_id' 
                         AND status = 'Available'
                         AND (SELECT CONCAT(departure_date, ' ', departure_time) 
                              FROM Flight_Schedule 
                              WHERE id = '$schedule_id') >= NOW()
                         LIMIT 1";

    $seat_check_result = $conn->query($seat_check_query);

    if ($seat_check_result && $seat_check_result->num_rows > 0) {
        // Proceed with booking if seats are available
        $conn->begin_transaction();

        try {
            // Insert into Transactions
            $transaction_query = "INSERT INTO Transactions (bill) 
                                  VALUES ('$bill')";
            if (!$conn->query($transaction_query)) {
                throw new Exception($conn->error);
            }

            $transaction_id = $conn->insert_id;

            // Insert into BookFlight (including airport_id)
            $booking_query = "INSERT INTO BookFlight (transaction_id, customer_id, flight_no, airport_id, schedule_id, ordered_seat) 
                              VALUES ($transaction_id, '$customer_id', 
                                      (SELECT flight_no FROM Flight_Schedule WHERE id = $schedule_id), 
                                      '$airport_id', $schedule_id, $ordered_seat)";
            if (!$conn->query($booking_query)) {
                throw new Exception($conn->error);
            }

            // Decrement available seats and update status to 'Booked'
            $update_seat_query = "UPDATE AllocateSeat 
                                  SET available_seats = available_seats - $ordered_seat, 
                                      status = CASE 
                                                WHEN available_seats - $ordered_seat > 0 THEN 'Available' 
                                                ELSE 'Booked' 
                                              END
                                  WHERE schedule_id = '$schedule_id' 
                                  AND status = 'Available'";
            if (!$conn->query($update_seat_query)) {
                throw new Exception($conn->error);
            }

            $conn->commit();
            echo "<p class='text-green-600 font-semibold'>Booking successful! Total Bill: $$bill</p>";
        } catch (Exception $e) {
            $conn->rollback();
            echo "<p class='text-red-600 font-semibold'>Error: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='text-red-600 font-semibold'>No available seats left for the selected flight or the schedule has passed.</p>";
    }
}

// Fetch flight schedules for the dropdown (only future schedules)
$schedule_query = "SELECT fs.id, f.flight_name, fs.departure_date, fs.departure_time, fs.source, fs.destination 
                   FROM Flight_Schedule fs
                   JOIN Flights f ON fs.flight_no = f.flight_no
                   WHERE CONCAT(fs.departure_date, ' ', fs.departure_time) >= NOW()";
$schedule_result = $conn->query($schedule_query);

// Fetch available airports
$airport_query = "SELECT id, airport_name FROM Airports";
$airport_result = $conn->query($airport_query);

if (!$schedule_result || !$airport_result) {
    die("Error fetching flight schedules or airports: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Booking</title>
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
    <h1>Flight Booking System</h1>
    <form method="POST">
        <label for="schedule_id">Select Flight Schedule:</label>
        <select name="schedule_id" id="schedule_id" required>
            <?php if ($schedule_result->num_rows > 0): ?>
                <?php while ($row = $schedule_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>">
                        <?php echo $row['flight_name'] . " - " . $row['source'] . " to " . $row['destination'] . " on " . $row['departure_date'] . " at " . $row['departure_time']; ?>
                    </option>
                <?php endwhile; ?>
            <?php else: ?>
                <option value="" disabled>No flight schedules available</option>
            <?php endif; ?>
        </select><br>

        <label for="ordered_seat">Number of Seats:</label>
        <input type="number" name="ordered_seat" id="ordered_seat" min="1" required><br>

        <label for="seat_type">Seat Type:</label>
        <select name="seat_type" id="seat_type" required>
            <option value="Economy">Economy</option>
            <option value="Business">Business</option>
            <option value="FirstClass">First Class</option>
        </select><br>

        <label for="airport_id">Select Airport:</label>
        <select name="airport_id" id="airport_id" required>
            <?php if ($airport_result->num_rows > 0): ?>
                <?php while ($row = $airport_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>">
                        <?php echo $row['airport_name']; ?>
                    </option>
                <?php endwhile; ?>
            <?php else: ?>
                <option value="" disabled>No airports available</option>
            <?php endif; ?>
        </select><br>

        <button type="submit">Book Flight</button>
    </form>
</body>
</html>

<?php $conn->close(); ?>
