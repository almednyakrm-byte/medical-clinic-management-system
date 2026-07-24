<?php
// edit_appointments.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
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
    <div class="max-w-md mx-auto mt-10 bg-white p-8 rounded-xl shadow-md">
        <h2 class="text-3xl text-blue-500 mb-4">Edit Appointment</h2>
        <form id="edit-appointment-form">
            <div class="mb-4">
                <label for="title" class="block text-blue-500 text-sm font-bold mb-2">Title</label>
                <input type="text" id="title" name="title" class="bg-gray-50 border border-gray-300 text-blue-500 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div class="mb-4">
                <label for="date" class="block text-blue-500 text-sm font-bold mb-2">Date</label>
                <input type="date" id="date" name="date" class="bg-gray-50 border border-gray-300 text-blue-500 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div class="mb-4">
                <label for="time" class="block text-blue-500 text-sm font-bold mb-2">Time</label>
                <input type="time" id="time" name="time" class="bg-gray-50 border border-gray-300 text-blue-500 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-blue-500 text-sm font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="bg-gray-50 border border-gray-300 text-blue-500 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"></textarea>
            </div>
            <button type="submit" class="text-white bg-blue-500 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update Appointment</button>
        </form>
    </div>

    <script>
        const id = <?php echo $id; ?>;
        const form = document.getElementById('edit-appointment-form');

        // Fetch existing record details
        fetch(`../backend/appointments.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('title').value = data.title;
                document.getElementById('date').value = data.date;
                document.getElementById('time').value = data.time;
                document.getElementById('description').value = data.description;
            });

        // Submit form
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/appointments.php', {
                method: 'PUT',
                body: JSON.stringify({
                    id: id,
                    title: formData.get('title'),
                    date: formData.get('date'),
                    time: formData.get('time'),
                    description: formData.get('description')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_appointments.php';
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>