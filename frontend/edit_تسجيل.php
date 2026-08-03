**edit_تسجيل.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$url = '../backend/تسجيل.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data is available
if ($data) {
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
} else {
    echo "Error fetching data";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit تسجيل</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 text-lg font-bold mb-4">Edit تسجيل</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="text-slate-900 block text-sm font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" class="w-full p-2 text-sm text-gray-600 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" value="<?= $name ?>">
            </div>
            <div>
                <label for="email" class="text-slate-900 block text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full p-2 text-sm text-gray-600 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" value="<?= $email ?>">
            </div>
            <div>
                <label for="phone" class="text-slate-900 block text-sm font-bold mb-2">Phone</label>
                <input type="tel" id="phone" name="phone" class="w-full p-2 text-sm text-gray-600 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" value="<?= $phone ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/تسجيل.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/تسجيل.php**

<?php
// Check if ID is provided
if (!isset($_GET['id'])) {
    echo "Error: ID not provided";
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Check if ID is valid
if (!is_numeric($id)) {
    echo "Error: Invalid ID";
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch existing record details
$sql = "SELECT * FROM تسجيل WHERE id = '$id'";
$result = $conn->query($sql);

// Check if data is available
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo "Error fetching data";
}

// Close connection
$conn->close();
?>