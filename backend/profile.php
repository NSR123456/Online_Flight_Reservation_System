<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['passport_id'])) {
    header("Location: user_login.php");
    exit();
}

// Retrieve user data from session
$passport_id = $_SESSION['passport_id'];
$name = $_SESSION['name'];
$dob = $_SESSION['dob'];
$nationality = $_SESSION['nationality'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-900">

    <div class="container mx-auto p-8">
        <div class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
            <h1 class="text-3xl font-semibold mb-4 text-center">Welcome, <?php echo $name; ?></h1>
            <div class="text-lg">
                <p class="mb-2"><strong>Passport ID:</strong> <?php echo $passport_id; ?></p>
                <p class="mb-2"><strong>Date of Birth:</strong> <?php echo $dob; ?></p>
                <p class="mb-2"><strong>Nationality:</strong> <?php echo $nationality; ?></p>
            </div>
            <!-- You can add more profile-related content here -->
        </div>
    </div>

</body>
</html>
