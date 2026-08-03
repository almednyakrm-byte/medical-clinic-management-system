**edit_خدمات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$data = json_decode(file_get_contents('../backend/خدمات.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit Service</h2>
        <form id="edit-service-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 text-sm text-gray-900 border-gray-300 rounded-lg" value="<?= $data['name'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 text-sm text-gray-900 border-gray-300 rounded-lg"><?= $data['description'] ?></textarea>
            </div>
            <div>
                <label for="price" class="block text-sm font-medium text-slate-700">Price</label>
                <input type="number" id="price" name="price" class="block w-full p-2 text-sm text-gray-900 border-gray-300 rounded-lg" value="<?= $data['price'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update Service</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-service-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/خدمات.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_خدمات.php';
                        } else {
                            alert('Error updating service');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/خدمات.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get ID
$id = $_GET['id'];

// Connect to database
$conn = new PDO('sqlite:database.db');

// Fetch existing record details
$stmt = $conn->prepare('SELECT * FROM services WHERE id = :id');
$stmt->bindParam(':id', $id);
$stmt->execute();
$data = $stmt->fetch();

// Close database connection
$conn = null;

// Output JSON data
header('Content-Type: application/json');
echo json_encode($data);