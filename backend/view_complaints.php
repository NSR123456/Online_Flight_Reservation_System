<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database credentials
$servername = "localhost";
$username = "panjas";
$password = "Panjas@cse1";
$dbname = "flight_reservation_system";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch customer messages
$query = "
    SELECT 
        sr.customer_id, 
        c.name AS customer_name, 
        sr.airline_id, 
        a.airline_name, 
        csi.message, 
        csi.email, 
        csi.created_at 
        
    FROM 
        SupportRequests sr
    JOIN customer_support_info csi ON sr.msg_id = csi.id
    JOIN Customers c ON sr.customer_id = c.passport_id
    JOIN Airlines a ON sr.airline_id = a.airline_id
    ORDER BY 
        csi.created_at DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Customer Messages</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
        .container { max-width: 1000px; margin: 30px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h1 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .email { font-weight: bold; }
        .email.Pending { color: orange; }
        .email.Resolved { color: green; }
        .email.InProgress { color: blue; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Customer Messages</h1>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Customer ID</th>
                        <th>Customer Name</th>
                        <th>Airline ID</th>
                        <th>Airline Name</th>
                        <th>Message</th>
                        <th>Email</th> <!-- Replaced 'Status' with 'Email' -->
                        <th>Submitted At</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['customer_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['airline_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['airline_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['message']); ?></td>
                            <td class="email <?php echo htmlspecialchars($row['email'] ?: ''); ?>">
                                <?php echo htmlspecialchars($row['email'] ?: 'No email provided'); ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No customer messages found.</p>
        <?php endif; ?>

    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
