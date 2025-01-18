<?php
// Admin Dashboard Backend Logic
ini_set('display_errors', 1);
error_reporting(E_ALL);
$servername = "localhost";
$username = "panjas";
$password = "Panjas@cse1";
$dbname = "flight_reservation_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Flight Management Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_flight'])) {
        $flight_no = $_POST['flight_no'];
        $flight_name = $_POST['flight_name'];
        $no_of_seat = $_POST['no_of_seat'];
        $engine = $_POST['engine'];
        $capacity = $_POST['capacity'];

        $sql = "INSERT INTO Flights (flight_no, flight_name, no_of_seat, engine, capacity)
                VALUES ('$flight_no', '$flight_name', '$no_of_seat', '$engine', '$capacity')";
        if ($conn->query($sql) === TRUE) {
            $success = "Flight added successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }

    // Customer Management Logic
    if (isset($_POST['add_customer'])) {
        $passport_id = $_POST['passport_id'];
        $name = $_POST['name'];
        $dob = $_POST['dob'];
        $nationality = $_POST['nationality'];

        $sql = "INSERT INTO Customers (passport_id, name, dob, nationality)
                VALUES ('$passport_id', '$name', '$dob', '$nationality')";
        if ($conn->query($sql) === TRUE) {
            $success = "Customer added successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }

    // Cab Management Logic
    if (isset($_POST['add_cab'])) {
        $reg_no = $_POST['reg_no'];
        $capacity = $_POST['capacity'];
        $driver_name = $_POST['driver_name'];
        $phone_no = $_POST['phone_no'];
        $model = $_POST['model'];

        $sql = "INSERT INTO Cabs (reg_no, capacity, driver_name, phone_no, model)
                VALUES ('$reg_no', '$capacity', '$driver_name', '$phone_no', '$model')";
        if ($conn->query($sql) === TRUE) {
            $success = "Cab added successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }

    // Airline Management Logic
    if (isset($_POST['add_airline'])) {
        $airline_id = $_POST['airline_id'];
        $airline_name = $_POST['airline_name'];
        $headquarter = $_POST['headquarter'];
        $contact = $_POST['contact'];
        $website = $_POST['website'];
        $services = $_POST['services'];
        $operating_regions = $_POST['operating_regions'];

        $sql = "INSERT INTO Airlines (airline_id, airline_name, headquarter, contact, website, services, operating_regions)
                VALUES ('$airline_id', '$airline_name', '$headquarter', '$contact', '$website', '$services', '$operating_regions')";
        if ($conn->query($sql) === TRUE) {
            $success = "Airline added successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }

    // Schedule Management Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_schedule'])) {
    // Retrieve data from the form
    $flight_no = $_POST['flight_no'];
    $departure_date = $_POST['departure_date'];
    //$arrival_date = $_POST['arrival_date'];
    $source = $_POST['source'];
    $destination = $_POST['destination'];

    // Insert data into the Schedule table
    $sql = "INSERT INTO Flight_Schedule (flight_no, departure_date,  source, destination)
            VALUES ('$flight_no', '$departure_date',  '$source', '$destination')";
    
    if ($conn->query($sql) === TRUE) {
        $success = "Schedule added successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Cab Route Price Management Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_route_price'])) {
        $pickup_location = $_POST['pickup_location'];
        $dropoff_location = $_POST['dropoff_location'];
        $price = $_POST['price'];

        $sql = "INSERT INTO Cab_Route_Price (pickup_location, dropoff_location, price)
                VALUES ('$pickup_location', '$dropoff_location', '$price')";
        if ($conn->query($sql) === TRUE) {
            $success = "Cab Route Price added successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}



}

// Fetch Data for Display
$flights = $conn->query("SELECT * FROM Flights");
$customers = $conn->query("SELECT * FROM Customers");
$cabs = $conn->query("SELECT * FROM Cabs");
$airlines = $conn->query("SELECT * FROM Airlines");
// Fetch Cab Route Prices for display
$route_prices = $conn->query("SELECT * FROM Cab_Route_Price");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Include Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <div class="container mx-auto p-6">

        <h1 class="text-4xl font-semibold text-center mb-6">Admin Dashboard</h1>

        <?php if (!empty($success)) echo "<p class='text-green-500 text-center'>$success</p>"; ?>
        <?php if (!empty($error)) echo "<p class='text-red-500 text-center'>$error</p>"; ?>

         <!-- Link to View Data Tables -->
         <div class="text-center">
            <a href="view_flights.php" class="text-blue-600 hover:text-blue-700">View Flights</a> | 
            <a href="view_customers.php" class="text-blue-600 hover:text-blue-700">View Customers</a> | 
            <a href="view_cabs.php" class="text-blue-600 hover:text-blue-700">View Cabs</a> | 
            <a href="view_airlines.php" class="text-blue-600 hover:text-blue-700">View Airlines</a> | 
            <a href="view_schedule.php" class="text-blue-600 hover:text-blue-700">View Schedules</a> |
            <a href="view_route_prices.php" class="text-blue-600 hover:text-blue-700">View Cab Route Prices</a> | 
            <a href="view_complaints.php" class="text-blue-600 hover:text-blue-700">View Customer Messages</a>
        </div>
        <!-- Flight Management Form -->
        <div class="form-container bg-white p-6 rounded-lg shadow-lg mb-8">
            <h2 class="text-2xl font-semibold mb-4">Add New Flight</h2>
            <form method="POST" action="">
                <input type="text" name="flight_no" placeholder="Flight Number" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="flight_name" placeholder="Flight Name" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="number" name="no_of_seat" placeholder="Number of Seats" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="engine" placeholder="Engine Type" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="number" name="capacity" placeholder="Capacity" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" name="add_flight" class="w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Add Flight</button>
            </form>
        </div>

        <!-- Customer Management Form -->
        <div class="form-container bg-white p-6 rounded-lg shadow-lg mb-8">
            <h2 class="text-2xl font-semibold mb-4">Add New Customer</h2>
            <form method="POST" action="">
                <input type="text" name="passport_id" placeholder="Passport ID" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="name" placeholder="Customer Name" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="date" name="dob" placeholder="Date of Birth" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="nationality" placeholder="Nationality" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" name="add_customer" class="w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Add Customer</button>
            </form>
        </div>

        <!-- Cab Management Form -->
        <div class="form-container bg-white p-6 rounded-lg shadow-lg mb-8">
            <h2 class="text-2xl font-semibold mb-4">Add New Cab</h2>
            <form method="POST" action="">
                <input type="text" name="reg_no" placeholder="Registration Number" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="number" name="capacity" placeholder="Capacity" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="driver_name" placeholder="Driver's Name" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="phone_no" placeholder="Phone Number" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="model" placeholder="Cab Model" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" name="add_cab" class="w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Add Cab</button>
            </form>
        </div>


    <!-- Cab Route Price Management Form -->
<div class="form-container bg-white p-6 rounded-lg shadow-lg mb-8">
    <h2 class="text-2xl font-semibold mb-4">Add New Cab Route Price</h2>
    <form method="POST" action="">
        <input type="text" name="pickup_location" placeholder="Pickup Location" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="text" name="dropoff_location" placeholder="Dropoff Location" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="number" name="price" placeholder="Price" step="0.01" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" name="add_route_price" class="w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Add Route Price</button>
    </form>
</div>

        <!-- Airline Management Form -->
        <div class="form-container bg-white p-6 rounded-lg shadow-lg mb-8">
            <h2 class="text-2xl font-semibold mb-4">Add New Airline</h2>
            <form method="POST" action="">
                <input type="text" name="airline_id" placeholder="Airline ID" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="airline_name" placeholder="Airline Name" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="headquarter" placeholder="Headquarter" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="contact" placeholder="Contact Number" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="website" placeholder="Website" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="services" placeholder="Services" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="operating_regions" placeholder="Operating Regions" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" name="add_airline" class="w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Add Airline</button>
            </form>
        </div>
<!-- Add Schedule Form (Frontend) -->
<div class="form-container bg-white p-6 rounded-lg shadow-lg mb-8">
    <h2 class="text-2xl font-semibold mb-4">Add New Schedule</h2>
    <form method="POST" action="">
        <div class="mb-4">
            <label for="flight_no" class="block text-sm font-medium text-gray-700">Flight Number</label>
            <input type="text" name="flight_no" id="flight_no" placeholder="Flight Number" required class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-4">
            <label for="departure_date" class="block text-sm font-medium text-gray-700">Departure Date</label>
            <input type="datetime-local" name="departure_date" id="departure_date" required class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div class="mb-4">
            <label for="source" class="block text-sm font-medium text-gray-700">Source Location</label>
            <input type="text" name="source" id="source" placeholder="Source Location" required class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-4">
            <label for="destination" class="block text-sm font-medium text-gray-700">Destination Location</label>
            <input type="text" name="destination" id="destination" placeholder="Destination Location" required class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" name="add_schedule" class="w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Add Schedule</button>
        
    </form>
</div>

       

    </div>

    
</body>
</html>
