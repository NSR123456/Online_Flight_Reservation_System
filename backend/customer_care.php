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

// Database connection details
$servername = "localhost";
$username = "panjas";
$password = "Panjas@cse1";
$dbname = "flight_reservation_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch airlines for the dropdown
$airlines_query = "SELECT airline_id, airline_name FROM Airlines";
$airlines_result = $conn->query($airlines_query);

// Fetch flights for the dropdown
$flights_query = "
    SELECT F.flight_no, F.flight_name, A.airline_name 
    FROM Flights F 
    INNER JOIN BelongTo B ON F.flight_no = B.flight_no 
    INNER JOIN Airlines A ON B.airline_id = A.airline_id";
$flights_result = $conn->query($flights_query);

// Handling Contact Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $airline_id = $_POST['airline_id'];
    $flight_no = $_POST['flight_no'];
    $message = $_POST['message'];

    if (!empty($email) && !empty($airline_id) && !empty($flight_no) && !empty($message)) {
        // Start transaction to ensure atomicity
        $conn->begin_transaction();

        try {
            // Step 1: Fetch the last `id` from customer_support_info
            $result = $conn->query("SELECT MAX(id) AS last_id FROM customer_support_info");
            $row = $result->fetch_assoc();
            $last_id = $row['last_id'] ? $row['last_id'] : 0; // Set to 0 if no rows exist
            $new_id = $last_id + 1;

            // Step 2: Insert into customer_support_info
            $stmt1 = $conn->prepare("INSERT INTO customer_support_info (id, message, email) VALUES (?, ?, ?)");
            $stmt1->bind_param("iss", $new_id, $message, $email);
            $stmt1->execute();

            // Step 3: Insert into SupportRequests
            $stmt2 = $conn->prepare("INSERT INTO SupportRequests (customer_id, airline_id, flight_no, msg_id) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("sssi", $customer_id, $airline_id, $flight_no, $new_id);
            $stmt2->execute();

            // Commit transaction
            $conn->commit();

            echo "Complaint submitted successfully!";
        } catch (Exception $e) {
            // Rollback on failure
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }

        // Close statements
        $stmt1->close();
        $stmt2->close();
    } else {
        echo "All fields are required!";
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Support</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 800px; margin: 20px auto; }
        h1 { text-align: center; }
        .form-container { margin-top: 20px; }
        .form-container input, .form-container textarea, .form-container select { padding: 10px; width: 100%; margin-bottom: 10px; }
        .form-container button { padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        .instructions { font-size: 14px; color: #555; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Customer Support</h1>

        <!-- Support Request Form -->
        <div class="form-container">
            <h2>Contact Support</h2>

            <!-- Instructions for Message -->
            <div class="instructions">
                <p><strong>Please provide the following information in your message:</strong></p>
                <ul>
                    <li>Flight Date: If you have a specific flight you are referring to, please include the date.</li>
                    <li>Airport Information: Mention the airport (departure and arrival) for better assistance.</li>
                    <li>Lost Items: If you are looking for a lost item, please describe the item and its details.</li>
                    <li>Passenger Details: If you are trying to find someone, provide as much detail as possible (e.g., flight number, date, and passenger description).</li>
                    <li>Other Queries: Feel free to mention any other details related to your query.</li>
                </ul>
                <p>This will help us assist you faster and more efficiently. Thank you!</p>
            </div>

            <form method="POST" action="">
                <input type="text" name="email" placeholder="Enter Your Email For Contact:" required>
                
                <!-- Airline Dropdown -->
                <select name="airline_id" required>
                    <option value="" disabled selected>Select an Airline</option>
                    <?php if ($airlines_result && $airlines_result->num_rows > 0): ?>
                        <?php while ($airline = $airlines_result->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($airline['airline_id']); ?>">
                                <?php echo htmlspecialchars($airline['airline_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="" disabled>No Airlines Found</option>
                    <?php endif; ?>
                </select>
                
                <!-- Flight Dropdown -->
                <select name="flight_no" required>
                    <option value="" disabled selected>Select a Flight</option>
                    <?php if ($flights_result && $flights_result->num_rows > 0): ?>
                        <?php while ($flight = $flights_result->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($flight['flight_no']); ?>">
                                <?php echo htmlspecialchars($flight['flight_name'] . " (" . $flight['airline_name'] . ")"); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="" disabled>No Flights Found</option>
                    <?php endif; ?>
                </select>
                
                <textarea name="message" rows="4" placeholder="Your Message" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>
</body>
</html>
