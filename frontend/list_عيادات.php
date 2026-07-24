**list_عيادات.php**

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
    <title>عيادات</title>
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
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: center;
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
        <h1>عيادات</h1>
        <a href="index.php" class="text-indigo-500 hover:text-indigo-700">الرئيسية</a>
        <span class="text-slate-900">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-indigo-500 hover:text-indigo-700">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_عيادات.php'">إضافة جديد</button>
        <input type="search" class="search-bar" id="search" placeholder="بحث...">
        <table class="table">
            <thead>
                <tr>
                    <th>اسم العيادة</th>
                    <th>العنوان</th>
                    <th>التليفون</th>
                    <th>الإجراءات</th>
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

        searchInput.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const records = Array.from(recordsTable.children);
            records.forEach(record => {
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
                const response = await fetch('../backend/عيادات.php', { method: 'GET' });
                const data = await response.json();
                recordsTable.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.اسم العيادة}</td>
                        <td>${record.العنوان}</td>
                        <td>${record.التليفون}</td>
                        <td>
                            <a href="edit_عيادات.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
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
                const response = await fetch(`../backend/عيادات.php?id=${id}`, { method: 'DELETE' });
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


**backend/عيادات.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get records
$query = "SELECT * FROM عيادات";
$result = $conn->query($query);

// Fetch records
$records = array();
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

// Output records
echo json_encode($records);

// Close connection
$conn->close();
?>


**backend/عيادات.php (DELETE)**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get ID
$id = $_GET['id'];

// Delete record
$query = "DELETE FROM عيادات WHERE id = '$id'";
$conn->query($query);

// Close connection
$conn->close();
?>