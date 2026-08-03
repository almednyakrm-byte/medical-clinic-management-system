**edit_عيادات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$url = '../backend/عيادات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found';
    exit;
}

// Set page title
$page_title = 'Edit عيادات';

// Include header
include 'header.php';

?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $page_title ?></h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white shadow-md rounded-lg p-4">
        <div class="grid grid-cols-1 gap-4">
            <div class="col-span-2">
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['name'] ?>">
            </div>
            <div class="col-span-2">
                <label for="address" class="block text-sm font-medium text-slate-900">Address:</label>
                <input type="text" id="address" name="address" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['address'] ?>">
            </div>
            <div class="col-span-2">
                <label for="phone" class="block text-sm font-medium text-slate-900">Phone:</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['phone'] ?>">
            </div>
        </div>

        <!-- Submit button -->
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
    </form>
</main>

<!-- JavaScript -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/عيادات.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('address').value = data.address;
            document.getElementById('phone').value = data.phone;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', (e) => {
        e.preventDefault();

        // Send AJAX PUT request
        fetch('../backend/عيادات.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: <?= $id ?>,
                name: document.getElementById('name').value,
                address: document.getElementById('address').value,
                phone: document.getElementById('phone').value
            })
        })
        .then(response => response.json())
        .then(data => {
            // Redirect to list page
            window.location.href = 'list_عيادات.php';
        })
        .catch(error => console.error(error));
    });
</script>

<!-- Include footer -->
<?php include 'footer.php'; ?>


**backend/عيادات.php**

<?php
// Check if id exists
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'Invalid id'));
    exit;
}

// Get id
$id = $_GET['id'];

// Check if id exists in database
// Replace with your database query
if ($id == 1) {
    echo json_encode(array('name' => 'Example Name', 'address' => 'Example Address', 'phone' => 'Example Phone'));
} else {
    echo json_encode(array('error' => 'Record not found'));
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Get data
    $data = json_decode(file_get_contents('php://input'), true);

    // Update record in database
    // Replace with your database query
    echo json_encode(array('success' => 'Record updated successfully'));
}
?>