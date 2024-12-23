<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection parameters
$servername = "localhost";
$username = "panjas";
$password = "Panjas@cse1";
$dbname = "flight_reservation_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize test results array
$testResults = [];

// Helper function to send POST requests (for form submission)
function send_post_request($url, $postData) {
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($postData)
        ]
    ];
    $context = stream_context_create($options);
    return file_get_contents($url, false, $context);
}

// Test Flight Search (Simulate search)
function test_flight_search($conn) {
    $from_location = "New York";
    $to_location = "Los Angeles";
    $departure_date = "2024-12-25";

    // Simulate POST request for flight search
    $postData = [
        'from_location' => $from_location,
        'to_location' => $to_location,
        'departure_date' => $departure_date
    ];
    $response = send_post_request('http://localhost/backend/search_flight.php', $postData);

    // Check if the search returns any results (checking if table exists in the response)
    if (strpos($response, '<table>') !== false) {
        return "Flight search test passed.";
    } else {
        return "Flight search test failed (no flights found).";
    }
}

// Run the test
$testResults[] = test_flight_search($conn);

// Close connection
$conn->close();

// Output the test results
echo "<h2>Test Report</h2>";
foreach ($testResults as $result) {
    echo "<p>$result</p>";
}
?>
