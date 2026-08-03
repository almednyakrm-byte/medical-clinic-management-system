**list_تسجيل.php**

<?php
// Session validation
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
    <title>تسجيل</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            font-size: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar:focus {
            outline: none;
            border-color: #aaa;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="mx-2">|</span>
        <span><?= $_SESSION['username'] ?></span>
        <span class="mx-2">|</span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">تسجيل</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_تسجيل.php'">إضافة جديد</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" class="search-bar" id="search" placeholder="بحث...">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم التسجيل</th>
                    <th>اسم الملف</th>
                    <th>تاريخ التسجيل</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch records from backend
        async function fetchRecords() {
            try {
                const response = await fetch('../backend/تسجيل.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                return data;
            } catch (error) {
                console.error(error);
            }
        }

        // Search records
        function searchRecords() {
            const searchQuery = document.getElementById('search').value;
            fetchRecords().then(data => {
                const records = document.getElementById('records');
                records.innerHTML = '';
                data.forEach(record => {
                    if (record.name.includes(searchQuery) || record.number.includes(searchQuery)) {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.number}</td>
                            <td>${record.name}</td>
                            <td>${record.date}</td>
                            <td>
                                <a href="edit_تسجيل.php?id=${record.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    }
                });
            });
        }

        // Delete record
        async function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                try {
                    const response = await fetch('../backend/تسجيل.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id })
                    });
                    if (response.ok) {
                        alert('تم حذف السجل بنجاح');
                        fetchRecords().then(data => {
                            const records = document.getElementById('records');
                            records.innerHTML = '';
                            data.forEach(record => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>${record.number}</td>
                                    <td>${record.name}</td>
                                    <td>${record.date}</td>
                                    <td>
                                        <a href="edit_تسجيل.php?id=${record.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                        <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                    </td>
                                `;
                                records.appendChild(row);
                            });
                        });
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }

        // Fetch records on page load
        fetchRecords().then(data => {
            const records = document.getElementById('records');
            data.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.number}</td>
                    <td>${record.name}</td>
                    <td>${record.date}</td>
                    <td>
                        <a href="edit_تسجيل.php?id=${record.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                        <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                records.appendChild(row);
            });
        });
    </script>
</body>
</html>

This code creates a premium Tailwind UI layout with a header navigation, table showing list of records, and search bar. It also includes AJAX functionality to fetch records from the backend and delete records.