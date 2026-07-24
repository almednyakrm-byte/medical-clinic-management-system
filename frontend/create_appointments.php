<?php
// create_appointments.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

include_once '../config.php';

if (isset($_POST['save'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];

    $query = "INSERT INTO appointments (title, description, start_date, end_date, status) VALUES ('$title', '$description', '$start_date', '$end_date', '$status')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header('Location: list_appointments.php');
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 bg-white p-8 rounded-xl shadow-md">
        <h2 class="text-3xl text-blue-500 font-bold mb-4">Create Appointment</h2>
        <form id="create-appointment-form">
            <div class="mb-4">
                <label for="title" class="block text-blue-500 font-bold mb-2">Title:</label>
                <input type="text" id="title" name="title" class="block w-full p-2 border border-blue-500 rounded">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-blue-500 font-bold mb-2">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 border border-blue-500 rounded"></textarea>
            </div>
            <div class="mb-4">
                <label for="start_date" class="block text-blue-500 font-bold mb-2">Start Date:</label>
                <input type="date" id="start_date" name="start_date" class="block w-full p-2 border border-blue-500 rounded">
            </div>
            <div class="mb-4">
                <label for="end_date" class="block text-blue-500 font-bold mb-2">End Date:</label>
                <input type="date" id="end_date" name="end_date" class="block w-full p-2 border border-blue-500 rounded">
            </div>
            <div class="mb-4">
                <label for="status" class="block text-blue-500 font-bold mb-2">Status:</label>
                <select id="status" name="status" class="block w-full p-2 border border-blue-500 rounded">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Appointment</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-appointment-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/appointments.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_appointments.php';
                    }
                });
            });
        });
    </script>
</body>
</html>