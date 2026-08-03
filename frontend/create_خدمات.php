**create_خدمات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);

    if (!empty($name) && !empty($description) && !empty($price)) {
        // Insert new record into database
        $query = "INSERT INTO services (name, description, price) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $name, $description, $price);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_خدمات.php');
        exit;
    } else {
        $error = 'Please fill in all fields';
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-8">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8">
        <h2 class="text-slate-900 font-bold text-lg mb-4">Create New Service</h2>
        <form id="create-service-form" method="post">
            <div class="mb-4">
                <label for="name" class="block text-slate-900 text-sm font-bold mb-2">Service Name:</label>
                <input type="text" id="name" name="name" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-slate-900 text-sm font-bold mb-2">Service Description:</label>
                <textarea id="description" name="description" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required></textarea>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-slate-900 text-sm font-bold mb-2">Service Price:</label>
                <input type="number" id="price" name="price" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
            </div>
            <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create Service</button>
        </form>
        <?php if (isset($error)) : ?>
            <p class="text-red-500 text-sm mt-2"><?= $error ?></p>
        <?php endif; ?>
    </div>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-service-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/خدمات.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_خدمات.php';
                    } else {
                        alert('Error creating service');
                    }
                }
            });
        });
    });
</script>


**Note:** This code assumes you have the following files and directories:

* `config/db.php`: database connection file
* `includes/header.php`: header file
* `includes/footer.php`: footer file
* `backend/خدمات.php`: backend file for handling form submission
* `list_خدمات.php`: list page for services

Also, make sure to replace the `../backend/خدمات.php` URL with the actual URL of your backend file.