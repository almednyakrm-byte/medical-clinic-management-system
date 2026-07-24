**create_عيادات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة عيادة جديدة</h2>
        <form id="create-form" class="space-y-4">
            <div>
                <label for="name" class="text-slate-900 font-bold">اسم العيادة</label>
                <input type="text" id="name" name="name" class="w-full p-2 pl-10 text-sm text-slate-900 bg-gray-100 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div>
                <label for="address" class="text-slate-900 font-bold">عنوان العيادة</label>
                <input type="text" id="address" name="address" class="w-full p-2 pl-10 text-sm text-slate-900 bg-gray-100 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div>
                <label for="phone" class="text-slate-900 font-bold">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="w-full p-2 pl-10 text-sm text-slate-900 bg-gray-100 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div>
                <label for="email" class="text-slate-900 font-bold">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="w-full p-2 pl-10 text-sm text-slate-900 bg-gray-100 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/عيادات.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_عيادات.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>


**Note:** This code assumes you have jQuery and a backend PHP script (`عيادات.php`) to handle the form submission. The backend script should validate and process the form data, and return a success message or an error message to the frontend script.