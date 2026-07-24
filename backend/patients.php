<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $patient_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // SQL query structure: Select all patients or a specific patient by ID
    $sql = 'SELECT * FROM patients';
    $params = [];
    if ($patient_id) {
        $sql .= ' WHERE id = :id';
        $params[':id'] = $patient_id;
    }

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Output processing
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($patients);
}

// Handle POST requests
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $input = json_decode(file_get_contents('php://input'), true);
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'] ?? null, FILTER_SANITIZE_EMAIL);

    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }

    // SQL query structure: Insert a new patient
    $sql = 'INSERT INTO patients (name, email) VALUES (:name, :email)';
    $params = [
        ':name' => $name,
        ':email' => $email,
    ];

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Patient created successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to create patient']);
    }
}

// Handle PUT requests
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate and sanitize input
    $input = json_decode(file_get_contents('php://input'), true);
    $patient_id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'] ?? null, FILTER_SANITIZE_EMAIL);

    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }

    // SQL query structure: Update a patient
    $sql = 'UPDATE patients SET name = :name, email = :email WHERE id = :id';
    $params = [
        ':id' => $patient_id,
        ':name' => $name,
        ':email' => $email,
    ];

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Patient updated successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to update patient']);
    }
}

// Handle DELETE requests
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input
    $input = json_decode(file_get_contents('php://input'), true);
    $patient_id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }

    // SQL query structure: Delete a patient
    $sql = 'DELETE FROM patients WHERE id = :id';
    $params = [
        ':id' => $patient_id,
    ];

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Patient deleted successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to delete patient']);
    }
}

// Handle invalid requests
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}