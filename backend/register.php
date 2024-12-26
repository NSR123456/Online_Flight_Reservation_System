<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "panjas";
$password = "Panjas@cse1";
$dbname = "flight_reservation_system";

// Establish a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $passport_id = $_POST['passport_id'];
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $nationality = $_POST['nationality'];

    $sql = "INSERT INTO Customers (passport_id, name, dob, nationality) 
            VALUES ('$passport_id', '$name', '$dob', '$nationality')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<form method="POST">
    Passport ID: <input type="text" name="passport_id" required><br>
    Name: <input type="text" name="name" required><br>
    Date of Birth: <input type="date" name="dob" required><br>
    Nationality: <input type="text" name="nationality" required><br>
    <button type="submit">Register</button>
</form>
