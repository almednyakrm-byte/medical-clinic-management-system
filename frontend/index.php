<?php
// Session check
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة عيادات طبية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-blue-500 h-screen">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <div class="flex justify-end mb-4">
            <button class="bg-white rounded-lg p-2 hover:bg-blue-500 hover:text-white" onclick="logout()">تسجيل الخروج</button>
        </div>
        <div class="text-3xl text-white mb-4">مرحباً بك في نظام إدارة عيادات طبية</div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg p-4 shadow-md" id="patients-count">
                <!-- Patients count will be displayed here -->
            </div>
            <div class="bg-white rounded-lg p-4 shadow-md" id="appointments-count">
                <!-- Appointments count will be displayed here -->
            </div>
            <div class="bg-white rounded-lg p-4 shadow-md" id="doctors-count">
                <!-- Doctors count will be displayed here -->
            </div>
            <div class="bg-white rounded-lg p-4 shadow-md" id="medical-records-count">
                <!-- Medical records count will be displayed here -->
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
            <a href="patients.php" class="bg-white rounded-lg p-4 shadow-md text-blue-500 hover:bg-blue-500 hover:text-white">إدارة المرضى</a>
            <a href="appointments.php" class="bg-white rounded-lg p-4 shadow-md text-blue-500 hover:bg-blue-500 hover:text-white">إدارة المواعيد</a>
            <a href="doctors.php" class="bg-white rounded-lg p-4 shadow-md text-blue-500 hover:bg-blue-500 hover:text-white">إدارة الأطباء</a>
            <a href="medical_records.php" class="bg-white rounded-lg p-4 shadow-md text-blue-500 hover:bg-blue-500 hover:text-white">إدارة السجلات الطبية</a>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('api/patients_count.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('patients-count').innerHTML = `<div class="text-2xl mb-2">عدد المرضى</div><div class="text-5xl">${data.count}</div>`;
            });

        fetch('api/appointments_count.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('appointments-count').innerHTML = `<div class="text-2xl mb-2">عدد المواعيد</div><div class="text-5xl">${data.count}</div>`;
            });

        fetch('api/doctors_count.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('doctors-count').innerHTML = `<div class="text-2xl mb-2">عدد الأطباء</div><div class="text-5xl">${data.count}</div>`;
            });

        fetch('api/medical_records_count.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('medical-records-count').innerHTML = `<div class="text-2xl mb-2">عدد السجلات الطبية</div><div class="text-5xl">${data.count}</div>`;
            });

        function logout() {
            fetch('api/logout.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'login.php';
                    }
                });
        }
    </script>
</body>
</html>