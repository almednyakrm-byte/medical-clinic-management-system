**create_مرضى.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

// Include form
include 'create_مرضى_form.php';

// Include footer
include 'footer.php';
?>


**create_مرضى_form.php**

<?php
// Include form header
include 'form_header.php';
?>

<div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">إضافة مريض جديد</h2>
    <form id="create मरضى form" class="space-y-6">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-900">اسم المريض</label>
            <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        </div>
        <div>
            <label for="age" class="block text-sm font-medium text-slate-900">عمر المريض</label>
            <input type="number" id="age" name="age" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        </div>
        <div>
            <label for="address" class="block text-sm font-medium text-slate-900">عنوان المريض</label>
            <textarea id="address" name="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-slate-900">رقم هاتف المريض</label>
            <input type="tel" id="phone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-slate-900">بريد إلكتروني المريض</label>
            <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        </div>
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">إضافة</button>
    </form>
</div>

<?php
// Include form footer
include 'form_footer.php';
?>


**form_header.php**

<?php
// Include form header
?>
<div class="flex justify-between items-center mb-4">
    <h2 class="text-lg font-bold text-slate-900">إضافة مريض جديد</h2>
    <button type="button" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="cancel-button">إلغاء</button>
</div>


**form_footer.php**

<?php
// Include form footer
?>
<script>
    $(document).ready(function() {
        $('#create مرضى form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/مرضى.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_مرضى.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>


**footer.php**

<?php
// Include footer
?>
</body>
</html>


**header.php**

<?php
// Include header
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مريض جديد</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <?php
    // Include navigation
    include 'navigation.php';
    ?>


**navigation.php**

<?php
// Include navigation
?>
<nav class="bg-slate-900 py-4">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <a href="#" class="text-lg font-bold text-white">مرضى</a>
        <ul class="flex items-center space-x-4">
            <li><a href="#" class="text-sm font-medium text-white hover:text-indigo-500">قائمة المرضى</a></li>
            <li><a href="#" class="text-sm font-medium text-white hover:text-indigo-500">إضافة مريض جديد</a></li>
        </ul>
    </div>
</nav>