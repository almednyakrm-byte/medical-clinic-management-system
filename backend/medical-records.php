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

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if user is admin for specific record access
    if ($id && $_SESSION['user_role'] != 'admin' && $_SESSION['user_id'] != $id) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM medical_records WHERE id = :id OR user_id = :user_id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);

    // Execute query
    $stmt->execute();

    // Process output
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
}

// Handle POST requests
elseif ($method == 'POST') {
    // Validate and sanitize input
    $input = json_decode(file_get_contents('php://input'), true);
    $patient_name = filter_var($input['patient_name'] ?? null, FILTER_SANITIZE_STRING);
    $medical_history = filter_var($input['medical_history'] ?? null, FILTER_SANITIZE_STRING);
    $medications = filter_var($input['medications'] ?? null, FILTER_SANITIZE_STRING);
    $user_id = $_SESSION['user_id'];

    // Check for required fields
    if (!$patient_name || !$medical_history || !$medications) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Bad Request']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO medical_records (patient_name, medical_history, medications, user_id) VALUES (:patient_name, :medical_history, :medications, :user_id)');
    $stmt->bindParam(':patient_name', $patient_name);
    $stmt->bindParam(':medical_history', $medical_history);
    $stmt->bindParam(':medications', $medications);
    $stmt->bindParam(':user_id', $user_id);

    // Execute query
    $stmt->execute();

    // Process output
    $record_id = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $record_id]);
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate and sanitize input
    $input = json_decode(file_get_contents('php://input'), true);
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    $patient_name = filter_var($input['patient_name'] ?? null, FILTER_SANITIZE_STRING);
    $medical_history = filter_var($input['medical_history'] ?? null, FILTER_SANITIZE_STRING);
    $medications = filter_var($input['medications'] ?? null, FILTER_SANITIZE_STRING);

    // Check for required fields
    if (!$id || !$patient_name || !$medical_history || !$medications) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Bad Request']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE medical_records SET patient_name = :patient_name, medical_history = :medical_history, medications = :medications WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':patient_name', $patient_name);
    $stmt->bindParam(':medical_history', $medical_history);
    $stmt->bindParam(':medications', $medications);

    // Execute query
    $stmt->execute();

    // Process output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record updated successfully']);
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate and sanitize input
    $input = json_decode(file_get_contents('php://input'), true);
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);

    // Check for required fields
    if (!$id) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Bad Request']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM medical_records WHERE id = :id');
    $stmt->bindParam(':id', $id);

    // Execute query
    $stmt->execute();

    // Process output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record deleted successfully']);
}

// Handle invalid requests
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method Not Allowed']);
}