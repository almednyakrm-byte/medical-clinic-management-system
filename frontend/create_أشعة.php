**create_أشعة.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-20 xl:px-20">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة جديدة</h2>

        <form id="create-form" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="name" class="text-slate-900 font-bold">اسم</label>
                    <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="description" class="text-slate-900 font-bold">وصف</label>
                    <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required></textarea>
                </div>
                <div>
                    <label for="image" class="text-slate-900 font-bold">صورة</label>
                    <input type="file" id="image" name="image" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="status" class="text-slate-900 font-bold">حالة</label>
                    <select id="status" name="status" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                        <option value="">اختر حالة</option>
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">حفظ</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '../backend/أشعة.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'list_أشعة.php';
                    } else {
                        alert(response.message);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**header.php**

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أشعة</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <header class="bg-slate-900 py-4">
        <nav class="container mx-auto p-4 flex justify-between items-center">
            <a href="#" class="text-white font-bold text-lg">أشعة</a>
            <ul class="flex items-center space-x-4">
                <li><a href="#" class="text-white hover:text-indigo-500">الصفحة الرئيسية</a></li>
                <li><a href="#" class="text-white hover:text-indigo-500">الصفحة الثانية</a></li>
                <li><a href="#" class="text-white hover:text-indigo-500">الصفحة الثالثة</a></li>
            </ul>
        </nav>
    </header>


**footer.php**

<footer class="bg-slate-900 py-4">
    <div class="container mx-auto p-4 text-center text-white">
        &copy; 2023 أشعة. جميع الحقوق محفوظة.
    </div>
</footer>


**backend/أشعة.php**

<?php
// Include database connection
include 'db.php';

// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['image']) && isset($_POST['status'])) {
    // Insert data into database
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_FILES['image'];
    $status = $_POST['status'];

    $query = "INSERT INTO أشعة (name, description, image, status) VALUES ('$name', '$description', '$image', '$status')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(array('success' => true, 'message' => 'تم إضافة البيانات بنجاح'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'حدث خطأ أثناء إضافة البيانات'));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'لا توجد بيانات لإضافة'));
}
?>