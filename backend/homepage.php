<?php
// Sample homepage for Online Flight Reservation System
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Flight Reservation System</title>
    <!-- Tailwind CSS CDN for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Navbar -->
    <nav class="bg-blue-600 shadow-md fixed top-0 left-0 w-full z-10">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo or Name -->
                <a href="index.php" class="text-white text-2xl font-bold">Flight Reservation</a>

                <!-- Navbar Links -->
                <div class="hidden md:flex space-x-8">
                    <a href="book_flight.php" class="text-white hover:text-blue-300">Book Flight</a>
                    <a href="book_cab.php" class="text-white hover:text-blue-300">Book Cab</a>
                    <a href="cancel_booking.php" class="text-white hover:text-blue-300">Cancel Booking</a>
                    <a href="customer_care.php" class="text-white hover:text-blue-300">Customer Care</a>
                    <a href="register.php" class="text-white hover:text-blue-300">Register</a>
                    <a href="login.php" class="text-white hover:text-blue-300">Login</a>
                    <a href="profile.php" class="text-white hover:text-blue-300">Profile</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-blue-500 text-white py-32">
        <div class="container mx-auto text-center">
            <h1 class="text-5xl font-semibold mb-4">Welcome to FlightRes</h1>
            <p class="text-xl mb-8">Your one-stop destination for booking flights, cabs, and managing bookings effortlessly.</p>
            <a href="book_flight.php" class="bg-yellow-400 text-blue-600 px-6 py-3 text-xl rounded-lg hover:bg-yellow-500">Start Booking</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-semibold mb-8">Our Features</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Flight Booking Feature -->
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-2xl font-bold mb-4">Flight Booking</h3>
                    <p class="text-gray-700 mb-4">Book your flights to any destination with ease. Choose your seat, class, and flight schedule.</p>
                    <a href="book_flight.php" class="text-blue-500 hover:underline">Book Now</a>
                </div>
                <!-- Cab Booking Feature -->
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-2xl font-bold mb-4">Cab Booking</h3>
                    <p class="text-gray-700 mb-4">Get a comfortable cab for your airport transfers, city trips, and more with just a few clicks.</p>
                    <a href="book_cab.php" class="text-blue-500 hover:underline">Book Cab</a>
                </div>
                <!-- Booking Cancellation Feature -->
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-2xl font-bold mb-4">Cancel Booking</h3>
                    <p class="text-gray-700 mb-4">Need to cancel or modify your booking? We make it easy to change your travel plans.</p>
                    <a href="cancel_booking.php" class="text-blue-500 hover:underline">Cancel Booking</a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="bg-gray-50 py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-semibold mb-8">About FlightRes</h2>
            <p class="text-lg text-gray-700 mb-8">We provide a seamless experience for booking flights, cabs, and managing travel reservations online. Our system is designed to make your travel plans stress-free and convenient.</p>
            <a href="customer_care.php" class="bg-blue-600 text-white px-6 py-3 text-xl rounded-lg hover:bg-blue-700">Contact Customer Care</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white py-8">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 FlightRes. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
