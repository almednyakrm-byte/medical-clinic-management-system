**edit_medical-records.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/medical-records.php?id=' . $id;
$data = json_decode(file_get_contents($url), true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found';
    exit;
}

// Set form fields
$name = $data['name'];
$age = $data['age'];
$condition = $data['condition'];
$medication = $data['medication'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Medical Record</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-white">
    <div class="max-w-md mx-auto p-4 mt-4 bg-blue-500 rounded-lg">
        <h2 class="text-white text-2xl font-bold mb-4">Edit Medical Record</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="text-white">Name:</label>
                <input type="text" id="name" name="name" value="<?= $name ?>" class="w-full p-2 rounded-lg bg-gray-100 border border-gray-300">
            </div>
            <div>
                <label for="age" class="text-white">Age:</label>
                <input type="number" id="age" name="age" value="<?= $age ?>" class="w-full p-2 rounded-lg bg-gray-100 border border-gray-300">
            </div>
            <div>
                <label for="condition" class="text-white">Condition:</label>
                <input type="text" id="condition" name="condition" value="<?= $condition ?>" class="w-full p-2 rounded-lg bg-gray-100 border border-gray-300">
            </div>
            <div>
                <label for="medication" class="text-white">Medication:</label>
                <input type="text" id="medication" name="medication" value="<?= $medication ?>" class="w-full p-2 rounded-lg bg-gray-100 border border-gray-300">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Record</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/medical-records.php',
                    data: formData,
                    success: function(response) {
                        window.location.href = 'list_medical-records.php';
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>


**Note:** This code assumes that you have a `medical-records.php` file in the `../backend` directory that handles the PUT request and updates the record in the database. You will need to modify this file to match your database schema and update logic.