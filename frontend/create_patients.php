<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include the database connection file
include '../backend/db.php';

// Set the module slug
$mod_slug = 'patients';

// Set the page title
$page_title = 'Create Patient';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl text-blue-500 font-bold mb-4"><?php echo $page_title; ?></h1>
        <form id="create-patient-form">
            <div class="mb-4">
                <label for="name" class="block text-blue-500 text-sm font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" class="bg-white border border-blue-500 rounded py-2 px-4 w-full">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-blue-500 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="bg-white border border-blue-500 rounded py-2 px-4 w-full">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-blue-500 text-sm font-bold mb-2">Phone</label>
                <input type="text" id="phone" name="phone" class="bg-white border border-blue-500 rounded py-2 px-4 w-full">
            </div>
            <div class="mb-4">
                <label for="address" class="block text-blue-500 text-sm font-bold mb-2">Address</label>
                <input type="text" id="address" name="address" class="bg-white border border-blue-500 rounded py-2 px-4 w-full">
            </div>
            <div class="mb-4">
                <label for="date_of_birth" class="block text-blue-500 text-sm font-bold mb-2">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" class="bg-white border border-blue-500 rounded py-2 px-4 w-full">
            </div>
            <div class="mb-4">
                <label for="medical_history" class="block text-blue-500 text-sm font-bold mb-2">Medical History</label>
                <textarea id="medical_history" name="medical_history" class="bg-white border border-blue-500 rounded py-2 px-4 w-full"></textarea>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Patient</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-patient-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/patients.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>