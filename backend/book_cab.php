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
$host = 'localhost';
$db = 'flight_reservation_system';
$user = 'panjas';
$password = 'Panjas@cse1';

// Establish database connection
$conn = new mysqli($host, $user, $password, $db);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cab_reg_no = $_POST['cab_reg_no'];
    $pickup_location = $_POST['pickup_location'];
    $dropoff_location = $_POST['dropoff_location'];
    $booking_date = $_POST['booking_date']; // Get the date from user input

    // Fetch the price based on the pickup and dropoff locations
    $sql_price = "SELECT price FROM Cab_Route_Price WHERE pickup_location = '$pickup_location' AND dropoff_location = '$dropoff_location'";
    $result_price = $conn->query($sql_price);

    if ($result_price->num_rows > 0) {
        $row = $result_price->fetch_assoc();
        $price = $row['price'];

        // Step 1: Retrieve the route_id for the selected pickup and dropoff locations
        $sql_route_id = "SELECT id FROM Cab_Route_Price WHERE pickup_location = '$pickup_location' AND dropoff_location = '$dropoff_location'";
        $result_route_id = $conn->query($sql_route_id);

        if ($result_route_id->num_rows > 0) {
            $row = $result_route_id->fetch_assoc();
            $route_id = $row['id'];

            // Step 2: Insert into BookCab table with the retrieved route_id and the user-specified booking date
            $sql_insert = "INSERT INTO BookCab (route_id, cab_reg_no, customer_id, booking_date) 
                           VALUES ('$route_id', '$cab_reg_no', '$customer_id', '$booking_date')";
            if ($conn->query($sql_insert) === TRUE) {
                echo "Cab booked successfully! Price: " . $price;
            } else {
                echo "Error: " . $sql_insert . "<br>" . $conn->error;
            }
        } else {
            echo "Route not found!";
        }
    } else {
        echo "Price not found for this route!";
    }
}
?>

<!-- HTML Form -->
<form method="POST">
    <!-- Cab Registration No (Select available cabs) -->
    Cab Registration No: 
    <select name="cab_reg_no" required>
        <?php
        $sql_cabs = "SELECT reg_no FROM Cabs";
        $result_cabs = $conn->query($sql_cabs);
        if ($result_cabs->num_rows > 0) {
            while ($row = $result_cabs->fetch_assoc()) {
                echo "<option value='" . $row['reg_no'] . "'>" . $row['reg_no'] . "</option>";
            }
        }
        ?>
    </select><br>

    <!-- Pickup Location (Select available routes) -->
    Pickup Location: 
    <select name="pickup_location" id="pickup-location" required>
        <?php
        $sql_routes = "SELECT DISTINCT pickup_location FROM Cab_Route_Price";
        $result_routes = $conn->query($sql_routes);
        if ($result_routes->num_rows > 0) {
            while ($row = $result_routes->fetch_assoc()) {
                echo "<option value='" . $row['pickup_location'] . "'>" . $row['pickup_location'] . "</option>";
            }
        }
        ?>
    </select><br>

    <!-- Dropoff Location (Select available routes) -->
    Dropoff Location: 
    <select name="dropoff_location" id="dropoff-location" required>
        <?php
        $sql_routes = "SELECT DISTINCT dropoff_location FROM Cab_Route_Price";
        $result_routes = $conn->query($sql_routes);
        if ($result_routes->num_rows > 0) {
            while ($row = $result_routes->fetch_assoc()) {
                echo "<option value='" . $row['dropoff_location'] . "'>" . $row['dropoff_location'] . "</option>";
            }
        }
        ?>
    </select><br>

    <!-- Booking Date (User Input) -->
    Booking Date:
    <input type="date" name="booking_date" required><br>

    <!-- Display price dynamically after selecting locations -->
    <p id="price-display"></p>

    <button type="submit">Book Cab</button>
</form>

<script>
// JavaScript to dynamically show the price based on selected pickup and dropoff locations
document.querySelector('form').addEventListener('change', function() {
    var pickupLocation = document.querySelector('#pickup-location').value;
    var dropoffLocation = document.querySelector('#dropoff-location').value;

    if (pickupLocation && dropoffLocation) {
        fetch('<?php echo $_SERVER['PHP_SELF']; ?>?pickup=' + pickupLocation + '&dropoff=' + dropoffLocation)
            .then(response => response.json())
            .then(data => {
                if (data.price) {
                    document.getElementById('price-display').innerHTML = "Price: " + data.price;
                } else {
                    document.getElementById('price-display').innerHTML = "No price available for this route.";
                }
            });
    }
});
</script>

<?php
// Handle the dynamic price fetching based on user selections
if (isset($_GET['pickup']) && isset($_GET['dropoff'])) {
    $pickup = $_GET['pickup'];
    $dropoff = $_GET['dropoff'];

    // Fetch price from the Cab_Route_Price table
    $sql_price = "SELECT price FROM Cab_Route_Price WHERE pickup_location = '$pickup' AND dropoff_location = '$dropoff'";
    $result_price = $conn->query($sql_price);

    if ($result_price->num_rows > 0) {
        $row = $result_price->fetch_assoc();
        echo json_encode(['price' => $row['price']]);
    } else {
        echo json_encode(['price' => null]);
    }
}
?>
