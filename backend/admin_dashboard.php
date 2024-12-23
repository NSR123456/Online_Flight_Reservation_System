<?php
// Admin Dashboard Backend Logic

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
}

// Fetch Data for Display
$flights = $conn->query("SELECT * FROM Flights");
$customers = $conn->query("SELECT * FROM Customers");
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

        <!-- Flights List -->
        <h2 class="text-2xl font-semibold mb-4">Available Flights</h2>
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg mb-8">
            <thead>
                <tr>
                    <th class="p-4 text-left border-b">Flight Number</th>
                    <th class="p-4 text-left border-b">Flight Name</th>
                    <th class="p-4 text-left border-b">Seats Available</th>
                    <th class="p-4 text-left border-b">Engine</th>
                    <th class="p-4 text-left border-b">Capacity</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($flight = $flights->fetch_assoc()) { ?>
                <tr>
                    <td class="p-4 border-b"><?php echo $flight['flight_no']; ?></td>
                    <td class="p-4 border-b"><?php echo $flight['flight_name']; ?></td>
                    <td class="p-4 border-b"><?php echo $flight['no_of_seat']; ?></td>
                    <td class="p-4 border-b"><?php echo $flight['engine']; ?></td>
                    <td class="p-4 border-b"><?php echo $flight['capacity']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Customers List -->
        <h2 class="text-2xl font-semibold mb-4">Customers</h2>
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
            <thead>
                <tr>
                    <th class="p-4 text-left border-b">Passport ID</th>
                    <th class="p-4 text-left border-b">Name</th>
                    <th class="p-4 text-left border-b">Date of Birth</th>
                    <th class="p-4 text-left border-b">Nationality</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($customer = $customers->fetch_assoc()) { ?>
                <tr>
                    <td class="p-4 border-b"><?php echo $customer['passport_id']; ?></td>
                    <td class="p-4 border-b"><?php echo $customer['name']; ?></td>
                    <td class="p-4 border-b"><?php echo $customer['dob']; ?></td>
                    <td class="p-4 border-b"><?php echo $customer['nationality']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</body>
</html>

<?php
$conn->close();
?>
