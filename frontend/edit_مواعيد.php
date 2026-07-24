**edit_مواعيد.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/مواعيد.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found.';
    exit;
}

// Set page title
$pageTitle = 'Edit مواعيد';

// Include header
include 'header.php';

?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $pageTitle ?></h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white rounded-lg shadow-md p-4">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-slate-900">Title</label>
            <input type="text" id="title" name="title" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['title'] ?>">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $existingRecord['description'] ?></textarea>
        </div>
        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-slate-900">Date</label>
            <input type="date" id="date" name="date" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['date'] ?>">
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
    </form>
</div>

<!-- Include footer -->
<?php include 'footer.php'; ?>

<!-- JavaScript -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/مواعيد.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('title').value = data.title;
            document.getElementById('description').value = data.description;
            document.getElementById('date').value = data.date;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT request
    document.getElementById('edit-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../backend/مواعيد.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_مواعيد.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>


**backend/مواعيد.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'ID not set'));
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Check if record exists
$record = get_record($id);

if (empty($record)) {
    echo json_encode(array('error' => 'Record not found'));
    exit;
}

// Update record via PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $data);
    update_record($id, $data);
    echo json_encode(array('success' => true));
    exit;
}

// Get record details
function get_record($id) {
    // Database query to get record details
    // ...
    return $record;
}

// Update record
function update_record($id, $data) {
    // Database query to update record
    // ...
}
?>