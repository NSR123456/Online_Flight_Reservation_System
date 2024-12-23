<?php
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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    
    // Insert the support request into the database
    $sql = "INSERT INTO SupportRequests (name, email, message) VALUES ('$name', '$email', '$message')";
    if ($conn->query($sql) === TRUE) {
        $success = "Your message has been sent successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Fetching support contact info
$supportInfo = $conn->query("SELECT * FROM SupportContacts LIMIT 1");
$supportDetails = $supportInfo->fetch_assoc();

// Close the database connection
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
        .contact-info { margin-top: 30px; }
        .contact-info p { font-size: 1.2em; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Customer Support</h1>

        <?php if (!empty($success)) echo "<p style='color: green;'>$success</p>"; ?>
        <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>

        <!-- Support Request Form -->
        <div class="form-container">
            <h2>Contact Support</h2>
            <form method="POST" action="">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <textarea name="message" rows="4" placeholder="Your Message" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>

        <!-- Support Contact Information -->
        <div class="contact-info">
            <h2>How to Reach Us</h2>
            <p><strong>Email:</strong> <?php echo $supportDetails['email']; ?></p>
            <p><strong>Phone:</strong> <?php echo $supportDetails['phone']; ?></p>
            <p><strong>Live Chat:</strong> Available 24/7 via our <a href="<?php echo $supportDetails['chat_link']; ?>" target="_blank">Live Chat</a></p>
        </div>
    </div>
</body>
</html>
