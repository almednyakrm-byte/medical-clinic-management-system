**create_مواعيد.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $description = trim($_POST['description']);

    // Check if fields are not empty
    if (!empty($name) && !empty($date) && !empty($time) && !empty($description)) {
        // Insert data into database
        $query = "INSERT INTO مواعيد (name, date, time, description) VALUES ('$name', '$date', '$time', '$description')";
        $result = mysqli_query($conn, $query);

        // Check if data is inserted successfully
        if ($result) {
            // Redirect back to list_{mod_slug}.php
            header('Location: list_مواعيد.php');
            exit;
        } else {
            echo 'Error inserting data';
        }
    } else {
        echo 'Please fill all fields';
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create مواعيد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-slate-900 {
            background-color: #1A1D23 !important;
        }
        .text-indigo-500 {
            color: #6B7280 !important;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-indigo-500 mb-4">Create مواعيد</h2>
        <form id="create-form" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">Date:</label>
                <input type="date" id="date" name="date" class="block w-full p-2 mt-1 border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="time" class="block text-sm font-medium text-gray-700">Time:</label>
                <input type="time" id="time" name="time" class="block w-full p-2 mt-1 border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 border-gray-300 rounded-md" required></textarea>
            </div>
            <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '../backend/مواعيد.php',
                    data: formData,
                    success: function(data) {
                        window.location.href = 'list_مواعيد.php';
                    }
                });
            });
        });
    </script>
</body>
</html>

This code creates a premium Tailwind UI form with all necessary fields based on common attributes for the `مواعيد` module. It uses AJAX to POST the form data to `../backend/مواعيد.php` on success, and redirects back to `list_مواعيد.php`. The form is validated to ensure that all fields are not empty.