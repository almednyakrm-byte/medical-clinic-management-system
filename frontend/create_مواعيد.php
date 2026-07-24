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
require_once '../backend/db.php';

// Create new record
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Validate input
    if (!empty($name) && !empty($description) && !empty($date) && !empty($time)) {
        // Insert record into database
        $sql = "INSERT INTO مواعيد (name, description, date, time) VALUES ('$name', '$description', '$date', '$time')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Redirect back to list page
            header('Location: list_مواعيد.php');
            exit;
        } else {
            echo 'Error: ' . mysqli_error($conn);
        }
    } else {
        echo 'Please fill in all fields.';
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة موعد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1a1a;
        }
        .text-indigo-500 {
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-slate-900 text-indigo-500">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">إضافة موعد</h1>
        <form action="" method="post" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-lg font-bold mb-2">اسم المواعيد</label>
                <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-lg font-bold mb-2">وصف المواعيد</label>
                <textarea id="description" name="description" class="w-full p-2 border border-gray-300 rounded" required></textarea>
            </div>
            <div class="mb-4">
                <label for="date" class="block text-lg font-bold mb-2">تاريخ المواعيد</label>
                <input type="date" id="date" name="date" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="time" class="block text-lg font-bold mb-2">ساعة المواعيد</label>
                <input type="time" id="time" name="time" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/مواعيد.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response == 'success') {
                            window.location.href = 'list_مواعيد.php';
                        } else {
                            alert('Error: ' + response);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

Note: You need to replace `../backend/مواعيد.php` with the actual URL of your backend script that handles the form submission. Also, make sure to include the jQuery library in your HTML file for the AJAX request to work.