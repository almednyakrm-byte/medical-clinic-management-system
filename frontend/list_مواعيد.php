**list_مواعيد.php**

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
    <title>مواعيد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
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
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">مواعيد</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مواعيد.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>تاريخ</th>
                    <th>وقت</th>
                    <th>حالة</th>
                    <th>أクション</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/مواعيد.php'), true);
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['date'] . '</td>';
                    echo '<td>' . $record['time'] . '</td>';
                    echo '<td>' . $record['status'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_مواعيد.php?id=' . $record['id'] . '" class="text-indigo-500 hover:text-indigo-700">تعديل</a>';
                    echo '<button class="text-red-500 hover:text-red-700" onclick="deleteRecord(' . $record['id'] . ')">حذف</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search-input');
            const searchQuery = searchInput.value.trim();
            if (searchQuery !== '') {
                fetch('../backend/مواعيد.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const recordsTable = document.getElementById('records-table');
                        recordsTable.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.date}</td>
                                <td>${record.time}</td>
                                <td>${record.status}</td>
                                <td>
                                    <a href="edit_مواعيد.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/مواعيد.php')
                    .then(response => response.json())
                    .then(data => {
                        const recordsTable = document.getElementById('records-table');
                        recordsTable.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.date}</td>
                                <td>${record.time}</td>
                                <td>${record.status}</td>
                                <td>
                                    <a href="edit_مواعيد.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            }
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/مواعيد.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                });
            }
        }
    </script>
</body>
</html>

**backend/مواعيد.php**

<?php
// Fetch records from database
$records = array();
$records[] = array(
    'id' => 1,
    'date' => '2022-01-01',
    'time' => '10:00',
    'status' => 'مفعل'
);
$records[] = array(
    'id' => 2,
    'date' => '2022-01-02',
    'time' => '11:00',
    'status' => 'مفعل'
);
$records[] = array(
    'id' => 3,
    'date' => '2022-01-03',
    'time' => '12:00',
    'status' => 'مفعل'
);

// Search query
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $records = array_filter($records, function($record) use ($searchQuery) {
        return strpos($record['date'], $searchQuery) !== false || strpos($record['time'], $searchQuery) !== false || strpos($record['status'], $searchQuery) !== false;
    });
}

// Output records as JSON
header('Content-Type: application/json');
echo json_encode($records);
?>

Note: This is a basic implementation and you should replace the backend code with your actual database connection and query logic.