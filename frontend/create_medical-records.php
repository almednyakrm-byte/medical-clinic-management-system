**create_medical-records.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'nav.php';

?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-lg font-bold text-blue-500 mb-4">Create Medical Record</h2>
        <form id="create-medical-record-form">
            <div class="mb-4">
                <label for="patient_name" class="block text-sm font-bold text-gray-700">Patient Name:</label>
                <input type="text" id="patient_name" name="patient_name" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="medical_condition" class="block text-sm font-bold text-gray-700">Medical Condition:</label>
                <input type="text" id="medical_condition" name="medical_condition" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="date_of_admission" class="block text-sm font-bold text-gray-700">Date of Admission:</label>
                <input type="date" id="date_of_admission" name="date_of_admission" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="date_of_discharge" class="block text-sm font-bold text-gray-700">Date of Discharge:</label>
                <input type="date" id="date_of_discharge" name="date_of_discharge" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="doctor_name" class="block text-sm font-bold text-gray-700">Doctor Name:</label>
                <input type="text" id="doctor_name" name="doctor_name" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Medical Record</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-medical-record-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/medical-records.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_medical-records.php';
                    } else {
                        alert('Error creating medical record');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**medical-records.php (backend)**

<?php
// Include database connection
include 'db.php';

// Check if form data is submitted
if (isset($_POST['patient_name']) && isset($_POST['medical_condition']) && isset($_POST['date_of_admission']) && isset($_POST['date_of_discharge']) && isset($_POST['doctor_name'])) {
    // Prepare SQL query
    $sql = "INSERT INTO medical_records (patient_name, medical_condition, date_of_admission, date_of_discharge, doctor_name) VALUES (?, ?, ?, ?, ?)";
    
    // Bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $_POST['patient_name'], $_POST['medical_condition'], $_POST['date_of_admission'], $_POST['date_of_discharge'], $_POST['doctor_name']);
    
    // Execute query
    $stmt->execute();
    
    // Check if query was successful
    if ($stmt->affected_rows === 1) {
        echo 'success';
    } else {
        echo 'Error creating medical record';
    }
    
    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>


**Note:** Make sure to replace `db.php` with your actual database connection file and `list_medical-records.php` with the actual URL of the page that lists medical records. Also, make sure to adjust the SQL query to match your actual database schema.