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

// Fetch flight data
$sql = "SELECT * FROM Flights";
$result = $conn->query($sql);

// Display flight data
echo "<h2>Flight List</h2>";
if ($result->num_rows > 0) {
    echo "<table><tr><th>Flight Number</th><th>Flight Name</th><th>Seats Available</th><th>Engine</th><th>Capacity</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['flight_no']}</td><td>{$row['flight_name']}</td><td>{$row['no_of_seat']}</td><td>{$row['engine']}</td><td>{$row['capacity']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "No flights found.";
}

$conn->close();
?>
