<?php
// edit_patients.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_patients.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl text-blue-500 font-bold mb-4">Edit Patient</h2>
        <form id="edit-patient-form">
            <div class="mb-4">
                <label for="name" class="block text-blue-500 font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 border border-gray-300 rounded-lg">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-blue-500 font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" class="block w-full p-2 border border-gray-300 rounded-lg">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-blue-500 font-bold mb-2">Phone:</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 border border-gray-300 rounded-lg">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Update Patient</button>
        </form>
    </div>

    <script>
        // Fetch existing patient record
        fetch('../backend/patients.php?id=<?php echo $id; ?>')
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('email').value = data.email;
                document.getElementById('phone').value = data.phone;
            });

        // Submit form using AJAX
        document.getElementById('edit-patient-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            fetch('../backend/patients.php', {
                method: 'PUT',
                body: JSON.stringify({
                    id: <?php echo $id; ?>,
                    name: formData.get('name'),
                    email: formData.get('email'),
                    phone: formData.get('phone')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_patients.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>