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

// Fetch airline data
$sql = "SELECT * FROM Airlines";
$result = $conn->query($sql);

// Display airline data
echo "<h2>Airline List</h2>";
if ($result->num_rows > 0) {
    echo "<table><tr><th>Airline ID</th><th>Airline Name</th><th>Headquarters</th><th>Contact</th><th>Website</th><th>Services</th><th>Operating Regions</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['airline_id']}</td><td>{$row['airline_name']}</td><td>{$row['headquarter']}</td><td>{$row['contact']}</td><td>{$row['website']}</td><td>{$row['services']}</td><td>{$row['operating_regions']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "No airlines found.";
}

$conn->close();
?>
