<?php
// edit_doctors.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_doctors.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-blue-500">Edit Doctor</h2>
        <form id="edit-doctor-form">
            <div class="mt-4">
                <label for="name" class="block text-blue-500">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg">
            </div>
            <div class="mt-4">
                <label for="email" class="block text-blue-500">Email:</label>
                <input type="email" id="email" name="email" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg">
            </div>
            <div class="mt-4">
                <label for="phone" class="block text-blue-500">Phone:</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg">
            </div>
            <div class="mt-4">
                <label for="specialty" class="block text-blue-500">Specialty:</label>
                <input type="text" id="specialty" name="specialty" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg">
            </div>
            <button type="submit" class="mt-4 py-2 px-4 bg-blue-500 text-white rounded-lg hover:bg-blue-700">Update Doctor</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-doctor-form');
        const id = <?php echo $id; ?>;

        // Fetch existing record details
        fetch(`../backend/doctors.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('email').value = data.email;
                document.getElementById('phone').value = data.phone;
                document.getElementById('specialty').value = data.specialty;
            });

        // Submit form with AJAX PUT request
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch(`../backend/doctors.php?id=${id}`, {
                method: 'PUT',
                body: JSON.stringify({
                    name: formData.get('name'),
                    email: formData.get('email'),
                    phone: formData.get('phone'),
                    specialty: formData.get('specialty')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_doctors.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>