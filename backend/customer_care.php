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
// Backend Logic for Handling Support Requests
$servername = "localhost";
$username = "panjas";
$password = "Panjas@cse1";
$dbname = "flight_reservation_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$airlines_query = "SELECT airline_id, airline_name FROM Airlines";
$airlines_result = $conn->query($airlines_query);
// Handling Contact Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email']; // Assume this comes from logged-in session or user input
    $airline_id = $_POST['airline_id'];
    $message = $_POST['message'];

    if (!empty($email) && !empty($airline_id) && !empty($message)) {
        // Start transaction to ensure atomicity
        $conn->begin_transaction();

        try {
             // Step 1: Fetch the last `id` from customer_support_info
             $result = $conn->query("SELECT MAX(id) AS last_id FROM customer_support_info");
             $row = $result->fetch_assoc();
             $last_id = $row['last_id'] ? $row['last_id'] : 0; // Set to 0 if no rows exist
             $new_id = $last_id + 1;
 
             // Step 2: Insert into customer_support_info with email
             $stmt1 = $conn->prepare("INSERT INTO customer_support_info (id, message, email) VALUES (?, ?, ?)");
             $stmt1->bind_param("iss", $new_id, $message, $email); // Now binding the email as well
             $stmt1->execute();
 
             // Step 3: Insert into SupportRequests
             $stmt2 = $conn->prepare("INSERT INTO SupportRequests (customer_id, airline_id, msg_id) VALUES (?, ?, ?)");
             $stmt2->bind_param("ssi", $customer_id, $airline_id, $new_id);
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
        .form-container input, .form-container textarea { padding: 10px; width: 100%; margin-bottom: 10px; }
        .form-container button { padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Customer Support</h1>

        <!-- Support Request Form -->
        <div class="form-container">
            <h2>Contact Support</h2>
            <form method="POST" action="">
                <input type="text" name="email" placeholder="Enter Your Email For Contact: " required>
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
                <textarea name="message" rows="4" placeholder="Your Message" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>
</body>
</html>
