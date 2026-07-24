**edit_مرضى.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/مرضى.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data is available
if ($data) {
    // Extract data
    $name = $data['name'];
    $age = $data['age'];
    $disease = $data['disease'];
} else {
    // Handle error
    echo 'Error fetching data';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit Patient</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-700">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $name ?>">
            </div>
            <div class="mb-4">
                <label for="age" class="block text-sm font-medium text-slate-700">Age:</label>
                <input type="number" id="age" name="age" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $age ?>">
            </div>
            <div class="mb-4">
                <label for="disease" class="block text-sm font-medium text-slate-700">Disease:</label>
                <input type="text" id="disease" name="disease" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $disease ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
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
                    url: '../backend/مرضى.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_مرضى.php';
                        } else {
                            alert('Error updating patient');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مرضى.php**

<?php
// Check if id is provided
if (!isset($_GET['id'])) {
    echo 'Error: ID not provided';
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch existing record details
$sql = "SELECT * FROM مرضى WHERE id = '$id'";
$result = $conn->query($sql);

// Check if data is available
if ($result->num_rows > 0) {
    // Extract data
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    // Handle error
    echo 'Error fetching data';
}

// Close connection
$conn->close();
?>


**Note:** Replace `'localhost'`, `'username'`, `'password'`, and `'database'` with your actual database credentials and name. Also, make sure to update the `list_مرضى.php` URL in the JavaScript code to match your actual file path.