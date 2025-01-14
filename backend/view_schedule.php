<?php
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

// Fetch flight schedule data
$sql = "SELECT * FROM Flight_Schedule";
$result = $conn->query($sql);

// Display flight schedule data
echo "<h2>Flight Schedule List</h2>";
if ($result->num_rows > 0) {
    echo "<table><tr><th>Schedule ID</th><th>Flight Number</th><th>Departure Date</th><th>Departure Time</th><th>From Location</th><th>To Location</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['id']}</td><td>{$row['flight_no']}</td><td>{$row['departure_date']}</td><td>{$row['departure_time']}</td><td>{$row['from_location']}</td><td>{$row['to_location']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "No flight schedules found.";
}

$conn->close();
?>
