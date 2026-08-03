**edit_سجلات-مريض.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via AJAX
$js = "
<script>
    $(document).ready(function() {
        $.get('../backend/سجلات-مريض.php?id=" . $id . "')
            .done(function(data) {
                $('#name').val(data.name);
                $('#age').val(data.age);
                $('#address').val(data.address);
            })
            .fail(function() {
                alert('Error fetching data');
            });
    });
</script>
";

// Include header
include 'header.php';

// Include form
include 'form.php';

// Include footer
include 'footer.php';
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.10/dist/sweetalert2.min.js"></script>
<script>
    $(document).ready(function() {
        // Submit form via AJAX
        $('#form').submit(function(event) {
            event.preventDefault();
            $.ajax({
                type: 'PUT',
                url: '../backend/سجلات-مريض.php',
                data: $(this).serialize(),
                success: function(data) {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Record updated successfully',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'list_{mod_slug}.php';
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error updating record',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>


**form.php**

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Edit سجلات مريض</h2>
    <form id="form" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-400 bg-white border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Name">
        </div>
        <div>
            <label for="age" class="block text-sm font-medium text-slate-900">Age</label>
            <input type="number" id="age" name="age" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-400 bg-white border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Age">
        </div>
        <div>
            <label for="address" class="block text-sm font-medium text-slate-900">Address</label>
            <textarea id="address" name="address" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-400 bg-white border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Address"></textarea>
        </div>
        <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-indigo-500 rounded-md hover:bg-indigo-600 focus:outline-none focus:border-indigo-700 focus:ring-1 focus:ring-indigo-700">Save Changes</button>
    </form>
</div>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit سجلات مريض</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <?php echo $js; ?>
    <?php echo $form; ?>
</body>
</html>


**footer.php**

<script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.js"></script>
</body>
</html>