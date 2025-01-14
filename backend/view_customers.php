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

// Fetch customer data
$sql = "SELECT * FROM Customers";
$result = $conn->query($sql);

// Display customer data
echo "<h2>Customer List</h2>";
if ($result->num_rows > 0) {
    echo "<table><tr><th>Passport ID</th><th>Name</th><th>Date of Birth</th><th>Nationality</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['passport_id']}</td><td>{$row['name']}</td><td>{$row['dob']}</td><td>{$row['nationality']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "No customers found.";
}

$conn->close();
?>
