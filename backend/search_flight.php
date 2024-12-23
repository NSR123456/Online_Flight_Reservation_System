<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "panjas";
$password = "Panjas@cse1";
$dbname = "flight_reservation_system";

// Establish a database connection
$conn = new mysqli($servername, $username, $password, $dbname);


// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Function to log errors to a file
function log_error($message) {
    error_log($message, 3, 'error_log.txt'); // Logs errors to 'error_log.txt' file
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $from_location = mysqli_real_escape_string($conn, $_POST['from_location']);
    $to_location = mysqli_real_escape_string($conn, $_POST['to_location']);
    $departure_date = mysqli_real_escape_string($conn, $_POST['departure_date']);

    // Prepare SQL query with error handling
    $sql = "SELECT fs.*, f.flight_name 
            FROM Flight_Schedule fs
            JOIN Flights f ON fs.flight_no = f.flight_no
            WHERE fs.from_location = '$from_location' 
              AND fs.to_location = '$to_location'
              AND fs.departure_date = '$departure_date'";

    if ($result = $conn->query($sql)) {
        // Check if there are results
        if ($result->num_rows > 0) {
            echo "<table><tr><th>Flight Name</th><th>Departure Date</th><th>Time</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['flight_name']}</td><td>{$row['departure_date']}</td><td>{$row['departure_time']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "No flights available.";
        }
        $result->free();
    } else {
        // Log the error and display a user-friendly message
        $error_message = "Error executing query: " . $conn->error;
        log_error($error_message);  // Log to the file
        echo "There was an error processing your request. Please try again later.";
    }
}
?>

<form method="POST">
    From: <input type="text" name="from_location" required><br>
    To: <input type="text" name="to_location" required><br>
    Departure Date: <input type="date" name="departure_date" required><br>
    <button type="submit">Search</button>
</form>
