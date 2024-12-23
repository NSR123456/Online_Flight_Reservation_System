<?php
// Set to show all errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$db = 'flight_reservation_system';
$user = 'panjas';
$password = 'Panjas@cse1';

// Establishing the connection
$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$log = ""; // Initialize log variable to store messages

// Handle flight search by flight number
if (isset($_POST['search_flight_no'])) {
    $flight_no = $_POST['search_flight_no'];

    // Log the flight_no value
    $log .= "Searching for Flight No: " . htmlspecialchars($flight_no) . "<br>";

    // SQL query to fetch flight details based on the flight number
    $sql = "SELECT * FROM Flights WHERE flight_no = '$flight_no'";

    // Execute the query
    $result = $conn->query($sql);

    // Check if the query was successful and data was retrieved
    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $log .= "Flight found: " . $row['flight_name'] . "<br>";  // Log successful query
?>

            <!-- Display the form with current flight details -->
            <form method="POST">
                Flight No: <input type="text" name="flight_no" value="<?php echo $row['flight_no']; ?>" readonly><br>
                Flight Name: <input type="text" name="flight_name" value="<?php echo $row['flight_name']; ?>"><br>
                Capacity: <input type="number" name="capacity" value="<?php echo $row['capacity']; ?>"><br>
                <button type="submit">Update Flight</button>
            </form>

<?php
        } else {
            // If no rows are found, log the error
            $log .= "No flight found with the provided flight number.<br>";
        }
    } else {
        // Log the query error if it fails
        $log .= "Error executing query: " . $conn->error . "<br>";
    }
}

// Handle form submission for updating the flight details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['flight_name'])) {
    // Fetch the form data
    $flight_no = $_POST['flight_no'];
    $flight_name = $_POST['flight_name'];
    $capacity = $_POST['capacity'];

    // SQL query to update flight details
    $update_sql = "UPDATE Flights SET flight_name = '$flight_name', capacity = '$capacity' WHERE flight_no = '$flight_no'";

    // Execute the update query
    if ($conn->query($update_sql) === TRUE) {
        $log .= "Flight updated successfully!<br>";  // Log successful update
    } else {
        $log .= "Error updating flight: " . $conn->error . "<br>";  // Log any error while updating
    }
}

// Close the connection
$conn->close();

// Display all logs and messages on the page
echo $log;
?>

<!-- Add a form to search for a flight by flight number -->
<form method="POST">
    <label for="search_flight_no">Search Flight by Flight Number:</label><br>
    <input type="text" name="search_flight_no" placeholder="Enter flight number" required><br>
    <button type="submit">Search</button>
</form>
