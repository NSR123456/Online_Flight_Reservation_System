<?php
// Admin Dashboard for Viewing Cab Route Prices
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

// Fetch Cab Route Prices for display, joining with Airports for the pickup location
$route_prices = $conn->query("
    SELECT 
        crp.id,
        a.airport_name AS pickup_location,
        crp.dropoff_location,
        crp.price 
    FROM BookCab bc
    JOIN Airports a ON bc.from_airport_id = a.id
    JOIN Cab_Route_Price crp ON crp.id = bc.route_id
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cab Route Prices</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <div class="container mx-auto p-6">
        <h1 class="text-4xl font-semibold text-center mb-6">Cab Route Prices</h1>

        <table class="min-w-full table-auto bg-white shadow-lg rounded-lg">
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="py-2 px-4 text-left">Pickup Location</th>
                    <th class="py-2 px-4 text-left">Dropoff Location</th>
                    <th class="py-2 px-4 text-left">Price</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($route_prices->num_rows > 0): ?>
                    <?php while ($row = $route_prices->fetch_assoc()): ?>
                        <tr>
                            <td class="py-2 px-4"><?= htmlspecialchars($row['pickup_location']); ?></td>
                            <td class="py-2 px-4"><?= htmlspecialchars($row['dropoff_location']); ?></td>
                            <td class="py-2 px-4"><?= htmlspecialchars($row['price']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="py-2 px-4 text-center">No route prices available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <a href="admin_dashboard.php" class="text-blue-600 hover:text-blue-700">Back to Dashboard</a>
        </div>
    </div>

</body>
</html>
