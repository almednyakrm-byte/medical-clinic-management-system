<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Current user info
$current_user = $_SESSION['user'];

// Logout function
function logout() {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-white hover:text-gray-200">Back to Index</a>
            <span>Welcome, <?php echo $current_user; ?></span>
            <button onclick="logout()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Logout</button>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl text-blue-500 mb-4">Appointments</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
            <a href="create_appointments.php" class="text-white">Add New Item</a>
        </button>
        <input type="text" id="search" placeholder="Search appointments" class="w-full p-2 mb-4 border border-gray-200 rounded">
        <table id="appointments-table" class="w-full table-auto border border-gray-200">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="p-2">ID</th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Date</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody id="appointments-tbody">
                <!-- Table data will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get appointments data
        fetch('../backend/appointments.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('appointments-tbody');
                data.forEach(appointment => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${appointment.id}</td>
                        <td>${appointment.name}</td>
                        <td>${appointment.date}</td>
                        <td>
                            <a href="edit_appointments.php?id=${appointment.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteAppointment(${appointment.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete appointment function
        function deleteAppointment(id) {
            fetch('../backend/appointments.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.querySelector(`#appointments-tbody tr:nth-child(${id})`);
                    row.remove();
                } else {
                    console.error('Error deleting appointment:', data.error);
                }
            });
        }

        // Search bar functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('#appointments-tbody tr');
            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>