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

// Fetch cab data
$sql = "SELECT * FROM Cabs";
$result = $conn->query($sql);

// Display cab data
echo "<h2>Cab List</h2>";
if ($result->num_rows > 0) {
    echo "<table><tr><th>Reg. No</th><th>Capacity</th><th>Driver Name</th><th>Phone No</th><th>Model</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['reg_no']}</td><td>{$row['capacity']}</td><td>{$row['driver_name']}</td><td>{$row['phone_no']}</td><td>{$row['model']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "No cabs found.";
}

$conn->close();
?>
