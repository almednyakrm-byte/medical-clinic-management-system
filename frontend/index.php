<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة عيادات طبية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-slate-900 text-white">
    <div class="container mx-auto p-4 pt-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">نظام إدارة عيادات طبية</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="glassmorphism p-4 mb-4">
            <h2 class="text-2xl font-bold mb-2">مرحباً بكم</h2>
            <p class="text-gray-300">نظام إدارة عيادات طبية مع حجز مواعيد وتصوير أشعة</p>
        </div>
        <div class="glassmorphism p-4 mb-4">
            <h2 class="text-2xl font-bold mb-2">إحصائيات</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-slate-800 rounded p-4">
                    <h3 class="text-lg font-bold mb-2">عيادات</h3>
                    <p id="clinics-count" class="text-gray-300"></p>
                </div>
                <div class="bg-slate-800 rounded p-4">
                    <h3 class="text-lg font-bold mb-2">مواعيد</h3>
                    <p id="appointments-count" class="text-gray-300"></p>
                </div>
                <div class="bg-slate-800 rounded p-4">
                    <h3 class="text-lg font-bold mb-2">أشعة</h3>
                    <p id="xray-count" class="text-gray-300"></p>
                </div>
            </div>
        </div>
        <div class="glassmorphism p-4 mb-4">
            <h2 class="text-2xl font-bold mb-2">روابط سريعة</h2>
            <ul class="list-none mb-0">
                <li class="mb-2"><a href="clinics.php" class="text-gray-300 hover:text-white">عيادات</a></li>
                <li class="mb-2"><a href="appointments.php" class="text-gray-300 hover:text-white">مواعيد</a></li>
                <li class="mb-2"><a href="xray.php" class="text-gray-300 hover:text-white">أشعة</a></li>
                <li class="mb-2"><a href="patients.php" class="text-gray-300 hover:text-white">مرضى</a></li>
            </ul>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('clinics-count').textContent = data.clinics_count;
                document.getElementById('appointments-count').textContent = data.appointments_count;
                document.getElementById('xray-count').textContent = data.xray_count;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and includes a session check to redirect to the login page if the user is not authenticated. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules. The stats are fetched dynamically via a Javascript API call from the backend files.

Note: You need to replace `/api/stats` with the actual API endpoint that returns the stats data. Also, make sure to create the necessary backend files to handle the API calls and return the stats data in JSON format.