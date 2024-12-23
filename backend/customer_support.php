<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support</title>
    <!-- Include Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-900">



    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-semibold text-center mb-6">Support</h1>
        <p class="text-lg text-center mb-6">If you need assistance, please reach out to us through the contact form below.</p>

        <h2 class="text-2xl font-semibold mb-4">Contact Us</h2>
        <p class="mb-6">For any issues or inquiries, please provide your details and message, and we will get back to you as soon as possible.</p>

        <!-- Contact Form -->
        <form method="POST" action="handle_support.php" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-semibold">Full Name:</label>
                <input type="text" id="name" name="name" required class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold">Email:</label>
                <input type="email" id="email" name="email" required class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="message" class="block text-gray-700 font-semibold">Message:</label>
                <textarea id="message" name="message" required class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <button type="submit" name="submit" class="w-full bg-blue-600 text-white p-3 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Submit
            </button>
        </form>
    </div>
</body>
</html>
