<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

// Handling Contact Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $airline_id = $_POST['airline_id'];
    $messages = $_POST['messages'];

    // Validate input
    if (!empty($customer_id) && !empty($airline_id) && !empty($messages)) {
        // Use a direct query instead of a prepared statement
        $query = "INSERT INTO SupportRequests (customer_id, airline_id, messages) VALUES ('$customer_id', '$airline_id', '$messages')";

        if ($conn->query($query) === TRUE) {
            echo "We are reviewing your issue. Stay with us. You will be sent an email shortly.";
        } else {
            echo "Error: Unable to submit your request. Please try again later.";
        }
    } else {
        echo "All fields are required. Please fill in all the details.";
    }
}

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
                <input type="text" name="customer_id" placeholder="Your Customer ID" required>
                <input type="text" name="airline_id" placeholder="Airline ID" required>
                <textarea name="messages" rows="4" placeholder="Your Message" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>
</body>
</html>
