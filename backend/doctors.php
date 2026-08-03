<?php
// Import database connection
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Get request data
$data = json_decode(file_get_contents('php://input'), true);

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize input
    $doctor_id = isset($data['id']) ? (int) $data['id'] : null;

    // SQL query structure
    if ($doctor_id) {
        $stmt = $pdo->prepare('SELECT * FROM doctors WHERE id = :id');
        $stmt->bindParam(':id', $doctor_id);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM doctors');
    }

    // Execute query
    $stmt->execute();

    // Output processing
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($doctors);
}

// Handle POST requests
elseif ($method == 'POST') {
    // Validate and sanitize input
    $name = isset($data['name']) ? trim($data['name']) : null;
    $specialty = isset($data['specialty']) ? trim($data['specialty']) : null;
    $hospital = isset($data['hospital']) ? trim($data['hospital']) : null;

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('INSERT INTO doctors (name, specialty, hospital) VALUES (:name, :specialty, :hospital)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':specialty', $specialty);
    $stmt->bindParam(':hospital', $hospital);

    // Execute query
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Doctor created successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to create doctor']);
    }
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Validate and sanitize input
    $doctor_id = isset($data['id']) ? (int) $data['id'] : null;
    $name = isset($data['name']) ? trim($data['name']) : null;
    $specialty = isset($data['specialty']) ? trim($data['specialty']) : null;
    $hospital = isset($data['hospital']) ? trim($data['hospital']) : null;

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('UPDATE doctors SET name = :name, specialty = :specialty, hospital = :hospital WHERE id = :id');
    $stmt->bindParam(':id', $doctor_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':specialty', $specialty);
    $stmt->bindParam(':hospital', $hospital);

    // Execute query
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Doctor updated successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to update doctor']);
    }
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Validate and sanitize input
    $doctor_id = isset($data['id']) ? (int) $data['id'] : null;

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('DELETE FROM doctors WHERE id = :id');
    $stmt->bindParam(':id', $doctor_id);

    // Execute query
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Doctor deleted successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to delete doctor']);
    }
}

// Handle invalid requests
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}