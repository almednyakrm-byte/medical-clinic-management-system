**edit_دفع.php**

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

// Fetch existing record details
$existingRecord = json_decode(file_get_contents('../backend/دفع.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    header('Location: list_دفع.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit دفع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-slate-900 mb-4">Edit دفع</h1>
        <form id="edit-daf'">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm" value="<?= $existingRecord['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium text-slate-900">Amount</label>
                <input type="number" id="amount" name="amount" class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm" value="<?= $existingRecord['amount'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-daf').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/دفع.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_دفع.php';
                        } else {
                            alert('Error updating record');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error updating record: ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/دفع.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    header('Location: list_دفع.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = array();
// Assuming you have a database connection
// $db = new PDO('dsn', 'username', 'password');
// $stmt = $db->prepare('SELECT * FROM دفع WHERE id = :id');
// $stmt->bindParam(':id', $id);
// $stmt->execute();
// $existingRecord = $stmt->fetch();

// Return existing record details as JSON
echo json_encode($existingRecord);