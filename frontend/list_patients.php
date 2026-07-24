<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Current user info
$current_user = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-blue-500">
    <header class="bg-white py-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-blue-500 hover:text-blue-700">Back to Index</a>
            <span class="text-blue-500">Welcome, <?php echo $current_user; ?></span>
            <a href="logout.php" class="text-blue-500 hover:text-blue-700">Logout</a>
        </nav>
    </header>
    <main class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl text-white">Patients List</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
            <a href="create_patients.php" class="text-white">Add New Item</a>
        </button>
        <input type="text" id="search" class="bg-white text-blue-500 font-bold py-2 px-4 rounded mb-4 w-full" placeholder="Search patients...">
        <table id="patients-table" class="w-full text-white">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="patients-tbody">
                <!-- Table content will be populated by JavaScript -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch patients data from backend
        fetch('../backend/patients.php')
            .then(response => response.json())
            .then(data => {
                const patientsTable = document.getElementById('patients-tbody');
                data.forEach(patient => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${patient.id}</td>
                        <td class="px-4 py-2">${patient.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_patients.php?id=${patient.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-blue-500 hover:text-blue-700" onclick="deletePatient(${patient.id})">Delete</button>
                        </td>
                    `;
                    patientsTable.appendChild(row);
                });
            });

        // Delete patient using AJAX
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
                    // Remove the deleted patient from the table
                    const patientsTable = document.getElementById('patients-tbody');
                    const rows = patientsTable.children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            patientsTable.removeChild(rows[i]);
                            break;
                        }
                    }
                } else {
                    console.error('Error deleting patient:', data.error);
                }
            });
        }

        // Search patients in real-time
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const patientsTable = document.getElementById('patients-tbody');
            const rows = patientsTable.children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const patientName = row.children[1].textContent.toLowerCase();
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