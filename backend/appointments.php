<?php
// Import database connection file
require_once 'db.php';

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Function to validate user role
function validateUserRole($role) {
    // Assuming a session variable 'user_role' is set
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $role) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
}

// Function to validate and sanitize input data
function validateAndSanitizeInput($data) {
    $sanitizedData = [];
    foreach ($data as $key => $value) {
        $sanitizedData[$key] = trim(htmlspecialchars($value));
    }
    return $sanitizedData;
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate user role
    validateUserRole('user');

    // Prepare SQL query to retrieve appointments
    $stmt = $pdo->prepare('SELECT * FROM appointments');
    $stmt->execute();

    // Fetch and return appointments
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($appointments);
}

// Handle POST requests
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate user role
    validateUserRole('user');

    // Read input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $sanitizedData = validateAndSanitizeInput($inputData);

    // Prepare SQL query to insert new appointment
    $stmt = $pdo->prepare('INSERT INTO appointments (title, description, date, time) VALUES (:title, :description, :date, :time)');
    $stmt->bindParam(':title', $sanitizedData['title']);
    $stmt->bindParam(':description', $sanitizedData['description']);
    $stmt->bindParam(':date', $sanitizedData['date']);
    $stmt->bindParam(':time', $sanitizedData['time']);

    // Execute SQL query and return response
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Appointment created successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to create appointment']);
    }
}

// Handle PUT requests
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate user role (admin-only)
    validateUserRole('admin');

    // Read input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $sanitizedData = validateAndSanitizeInput($inputData);

    // Prepare SQL query to update existing appointment
    $stmt = $pdo->prepare('UPDATE appointments SET title = :title, description = :description, date = :date, time = :time WHERE id = :id');
    $stmt->bindParam(':title', $sanitizedData['title']);
    $stmt->bindParam(':description', $sanitizedData['description']);
    $stmt->bindParam(':date', $sanitizedData['date']);
    $stmt->bindParam(':time', $sanitizedData['time']);
    $stmt->bindParam(':id', $sanitizedData['id']);

    // Execute SQL query and return response
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Appointment updated successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to update appointment']);
    }
}

// Handle DELETE requests
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate user role (admin-only)
    validateUserRole('admin');

    // Read input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $sanitizedData = validateAndSanitizeInput($inputData);

    // Prepare SQL query to delete existing appointment
    $stmt = $pdo->prepare('DELETE FROM appointments WHERE id = :id');
    $stmt->bindParam(':id', $sanitizedData['id']);

    // Execute SQL query and return response
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Appointment deleted successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to delete appointment']);
    }
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}

// Close database connection
$pdo = null;
?>