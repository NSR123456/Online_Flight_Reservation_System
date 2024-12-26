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
}

// Fetch Data for Display
$flights = $conn->query("SELECT * FROM Flights");
$customers = $conn->query("SELECT * FROM Customers");
$cabs = $conn->query("SELECT * FROM Cabs");
$airlines = $conn->query("SELECT * FROM Airlines");
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

        <!-- Display Flights, Customers, Cabs, Airlines -->
        <!-- Flights Table -->
        <div class="table-container bg-white p-6 rounded-lg shadow-lg mb-8">
            <h2 class="text-2xl font-semibold mb-4">Flight List</h2>
            <table class="table-auto w-full text-left">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3">Flight No</th>
                        <th class="p-3">Flight Name</th>
                        <th class="p-3">Seats Available</th>
                        <th class="p-3">Engine</th>
                        <th class="p-3">Capacity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $flights->fetch_assoc()): ?>
                        <tr>
                            <td class="p-3"><?= $row['flight_no'] ?></td>
                            <td class="p-3"><?= $row['flight_name'] ?></td>
                            <td class="p-3"><?= $row['no_of_seat'] ?></td>
                            <td class="p-3"><?= $row['engine'] ?></td>
                            <td class="p-3"><?= $row['capacity'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Cabs Table -->
        <div class="table-container bg-white p-6 rounded-lg shadow-lg mb-8">
            <h2 class="text-2xl font-semibold mb-4">Cab List</h2>
            <table class="table-auto w-full text-left">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3">Reg No</th>
                        <th class="p-3">Driver Name</th>
                        <th class="p-3">Capacity</th>
                        <th class="p-3">Model</th>
                        <th class="p-3">Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $cabs->fetch_assoc()): ?>
                        <tr>
                            <td class="p-3"><?= $row['reg_no'] ?></td>
                            <td class="p-3"><?= $row['driver_name'] ?></td>
                            <td class="p-3"><?= $row['capacity'] ?></td>
                            <td class="p-3"><?= $row['model'] ?></td>
                            <td class="p-3"><?= $row['phone_no'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Airlines Table -->
        <div class="table-container bg-white p-6 rounded-lg shadow-lg mb-8">
            <h2 class="text-2xl font-semibold mb-4">Airline List</h2>
            <table class="table-auto w-full text-left">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3">Airline ID</th>
                        <th class="p-3">Airline Name</th>
                        <th class="p-3">Headquarter</th>
                        <th class="p-3">Contact</th>
                        <th class="p-3">Website</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $airlines->fetch_assoc()): ?>
                        <tr>
                            <td class="p-3"><?= $row['airline_id'] ?></td>
                            <td class="p-3"><?= $row['airline_name'] ?></td>
                            <td class="p-3"><?= $row['headquarter'] ?></td>
                            <td class="p-3"><?= $row['contact'] ?></td>
                            <td class="p-3"><?= $row['website'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Customers Table -->
        <div class="table-container bg-white p-6 rounded-lg shadow-lg mb-8">
            <h2 class="text-2xl font-semibold mb-4">Customer List</h2>
            <table class="table-auto w-full text-left">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3">Passport ID</th>
                        <th class="p-3">Name</th>
                        <th class="p-3">Date of Birth</th>
                        <th class="p-3">Nationality</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $customers->fetch_assoc()): ?>
                        <tr>
                            <td class="p-3"><?= $row['passport_id'] ?></td>
                            <td class="p-3"><?= $row['name'] ?></td>
                            <td class="p-3"><?= $row['dob'] ?></td>
                            <td class="p-3"><?= $row['nationality'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>


        <!-- Add tables for Customers, Cabs, and Airlines below -->

    </div>
</body>
</html>
