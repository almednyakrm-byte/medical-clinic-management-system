**list_سجلات-مريض.php**

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
    <title>سجلات مريض</title>
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
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="ml-4">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="ml-4">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4 mt-4">
        <h2 class="text-2xl font-bold mb-4">سجلات مريض</h2>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_سجلات-مريض.php'">إضافة جديد</button>
        <div class="flex justify-end mt-4">
            <input type="search" class="search-bar" id="search" placeholder="بحث...">
        </div>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>اسم المريض</th>
                    <th>العمر</th>
                    <th>العلاج</th>
                    <th>حالة المريض</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsTable = document.getElementById('records');

        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const records = Array.from(recordsTable.children);
            records.forEach((record, index) => {
                const text = record.textContent.toLowerCase();
                if (text.includes(searchValue)) {
                    record.style.display = 'table-row';
                } else {
                    record.style.display = 'none';
                }
            });
        });

        async function loadRecords() {
            try {
                const response = await fetch('../backend/سجلات-مريض.php');
                const data = await response.json();
                recordsTable.innerHTML = '';
                data.forEach((record) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.اسم_المريض}</td>
                        <td>${record.العمر}</td>
                        <td>${record.العلاج}</td>
                        <td>${record.حالة_المريض}</td>
                        <td>
                            <a href="edit_سجلات-مريض.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                            <button class="ml-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    recordsTable.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        loadRecords();

        async function deleteRecord(id) {
            try {
                const response = await fetch('../backend/سجلات-مريض.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                });
                if (response.ok) {
                    loadRecords();
                } else {
                    console.error('Error deleting record');
                }
            } catch (error) {
                console.error(error);
            }
        }
    </script>
</body>
</html>


**Note:** This code assumes that you have a backend PHP file (`../backend/سجلات-مريض.php`) that handles GET and DELETE requests for the records. The backend file should return a JSON response with the records data.