<?php
// Backend Logic for Customer Login

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

// Initialize variables
$passport_id = "";
$error = "";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $passport_id = $_POST['passport_id'];

    // Query to check if the passport_id exists in the Customers table
    $sql = "SELECT * FROM Customers WHERE passport_id = '$passport_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Successful login
        $customer = $result->fetch_assoc();
        session_start();
        $_SESSION['passport_id'] = $customer['passport_id'];
        $_SESSION['name'] = $customer['name'];
        $_SESSION['dob'] = $customer['dob'];
        $_SESSION['nationality'] = $customer['nationality'];

        // Redirect to customer profile or dashboard
        header("Location: homepage.php");
        exit();
    } else {
        // Invalid passport_id
        $error = "Invalid Passport ID. Please try again.";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 400px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; }
        h2 { text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        button { width: 100%; padding: 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Customer Login</h2>
        
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <!-- Login Form -->
        <form method="POST" action="">
            <input type="text" name="passport_id" placeholder="Enter Passport ID" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
