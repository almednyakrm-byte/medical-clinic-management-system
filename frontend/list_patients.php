<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Current user info
$current_user = $_SESSION['username'];

// Patients list
$patients = array();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-white hover:text-gray-200">Back to Index</a>
            <span>Welcome, <?php echo $current_user; ?></span>
            <a href="logout.php" class="text-white hover:text-gray-200">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-2xl mb-4">Patients List</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <a href="create_patients.php" class="text-white">Add New Item</a>
            </button>
            <input type="text" id="search" class="py-2 px-4 border border-gray-400 rounded" placeholder="Search patients...">
        </div>
        <table id="patients-table" class="w-full table-auto border border-gray-400">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="patients-tbody">
                <!-- Patients list will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch patients list from backend
        fetch('../backend/patients.php')
            .then(response => response.json())
            .then(data => {
                const patientsTbody = document.getElementById('patients-tbody');
                data.forEach(patient => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${patient.id}</td>
                        <td class="px-4 py-2">${patient.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_patients.php?id=${patient.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deletePatient(${patient.id})">Delete</button>
                        </td>
                    `;
                    patientsTbody.appendChild(row);
                });
            });

        // Delete patient
        function deletePatient(id) {
            fetch('../backend/patients.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove patient from table
                        const patientsTable = document.getElementById('patients-table');
                        const rows = patientsTable.rows;
                        for (let i = 0; i < rows.length; i++) {
                            if (rows[i].cells[0].textContent == id) {
                                patientsTable.deleteRow(i);
                                break;
                            }
                        }
                    } else {
                        console.error('Error deleting patient:', data.error);
                    }
                });
        }

        // Search patients
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const patientsTable = document.getElementById('patients-table');
            const rows = patientsTable.rows;
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const patientName = row.cells[1].textContent.toLowerCase();
                if (patientName.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>