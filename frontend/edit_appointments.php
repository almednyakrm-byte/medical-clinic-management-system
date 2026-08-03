<?php
// edit_appointments.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_appointments.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl text-blue-500 font-bold mb-4">Edit Appointment</h2>
        <form id="edit-appointment-form">
            <div class="mb-4">
                <label for="title" class="block text-blue-500 font-bold mb-2">Title:</label>
                <input type="text" id="title" name="title" class="block w-full p-2 border border-blue-500 rounded-lg">
            </div>
            <div class="mb-4">
                <label for="date" class="block text-blue-500 font-bold mb-2">Date:</label>
                <input type="date" id="date" name="date" class="block w-full p-2 border border-blue-500 rounded-lg">
            </div>
            <div class="mb-4">
                <label for="time" class="block text-blue-500 font-bold mb-2">Time:</label>
                <input type="time" id="time" name="time" class="block w-full p-2 border border-blue-500 rounded-lg">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-blue-500 font-bold mb-2">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 border border-blue-500 rounded-lg"></textarea>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Update Appointment</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            const id = '<?php echo $id; ?>';
            $.ajax({
                type: 'GET',
                url: '../backend/appointments.php?id=' + id,
                dataType: 'json',
                success: function(data) {
                    $('#title').val(data.title);
                    $('#date').val(data.date);
                    $('#time').val(data.time);
                    $('#description').val(data.description);
                }
            });

            $('#edit-appointment-form').submit(function(e) {
                e.preventDefault();
                const formData = {
                    id: id,
                    title: $('#title').val(),
                    date: $('#date').val(),
                    time: $('#time').val(),
                    description: $('#description').val()
                };

                $.ajax({
                    type: 'PUT',
                    url: '../backend/appointments.php',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    success: function(data) {
                        window.location.href = 'list_appointments.php';
                    }
                });
            });
        });
    </script>
</body>
</html>