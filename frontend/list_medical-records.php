**list_medical-records.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Records</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .bg-blue-500 {
            background-color: #1a73e8;
        }
        .text-white {
            color: #ffffff;
        }
    </style>
</head>
<body class="bg-blue-500 text-white">
    <div class="container mx-auto p-4 pt-6">
        <div class="flex justify-between">
            <a href="index.php" class="text-white hover:text-gray-200">Back to Index</a>
            <div class="flex items-center">
                <span class="text-white mr-2">Welcome, <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="text-white hover:text-gray-200">Logout</a>
            </div>
        </div>
        <div class="mt-4">
            <h2 class="text-2xl text-white">Medical Records</h2>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_medical-records.php'">Add New Item</button>
        </div>
        <div class="mt-4">
            <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search...">
            <div id="records" class="mt-4"></div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsContainer = document.getElementById('records');

        searchInput.addEventListener('input', () => {
            const searchQuery = searchInput.value.toLowerCase();
            const records = document.querySelectorAll('.record');
            records.forEach(record => {
                const recordText = record.textContent.toLowerCase();
                if (recordText.includes(searchQuery)) {
                    record.style.display = 'block';
                } else {
                    record.style.display = 'none';
                }
            });
        });

        async function fetchRecords() {
            try {
                const response = await fetch('../backend/medical-records.php');
                const data = await response.json();
                const recordsHtml = data.map(record => `
                    <div class="record mb-4 p-4 bg-white rounded shadow-md">
                        <h3 class="text-lg font-bold">${record.name}</h3>
                        <p>${record.description}</p>
                        <div class="flex justify-between">
                            <a href="edit_medical-records.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">Delete</button>
                        </div>
                    </div>
                `).join('');
                recordsContainer.innerHTML = recordsHtml;
            } catch (error) {
                console.error(error);
            }
        }

        async function deleteRecord(id) {
            try {
                const response = await fetch('../backend/medical-records.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                if (response.ok) {
                    fetchRecords();
                } else {
                    console.error('Error deleting record');
                }
            } catch (error) {
                console.error(error);
            }
        }

        fetchRecords();
    </script>
</body>
</html>


**medical-records.php (backend)**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch records
$query = "SELECT * FROM medical_records";
$result = $conn->query($query);

// Get records as JSON
$records = array();
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}
echo json_encode($records);

// Delete record
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM medical_records WHERE id = '$id'";
    $conn->query($query);
}

// Close connection
$conn->close();
?>


Note: This is a basic example and you should adjust the code to fit your specific needs. Also, make sure to replace the placeholders with your actual database credentials and table name.