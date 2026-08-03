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
    $patient_id = filter_var($_GET['patient_id'] ?? null, FILTER_VALIDATE_INT);
    if ($patient_id === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid patient ID']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('SELECT * FROM سجلات_مريض WHERE patient_id = :patient_id');
    $stmt->bindParam(':patient_id', $patient_id);
    $stmt->execute();

    // Process output
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
}

// Handle POST requests
elseif ($method == 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $patient_id = filter_var($input['patient_id'] ?? null, FILTER_VALIDATE_INT);
    $record_date = filter_var($input['record_date'] ?? null, FILTER_VALIDATE_DATE);
    $record_details = filter_var($input['record_details'] ?? null, FILTER_SANITIZE_STRING);
    if ($patient_id === false || $record_date === false || $record_details === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('INSERT INTO سجلات_مريض (patient_id, record_date, record_details) VALUES (:patient_id, :record_date, :record_details)');
    $stmt->bindParam(':patient_id', $patient_id);
    $stmt->bindParam(':record_date', $record_date);
    $stmt->bindParam(':record_details', $record_details);
    $stmt->execute();

    // Process output
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record created successfully']);
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

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $record_id = filter_var($input['record_id'] ?? null, FILTER_VALIDATE_INT);
    $patient_id = filter_var($input['patient_id'] ?? null, FILTER_VALIDATE_INT);
    $record_date = filter_var($input['record_date'] ?? null, FILTER_VALIDATE_DATE);
    $record_details = filter_var($input['record_details'] ?? null, FILTER_SANITIZE_STRING);
    if ($record_id === false || $patient_id === false || $record_date === false || $record_details === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('UPDATE سجلات_مريض SET patient_id = :patient_id, record_date = :record_date, record_details = :record_details WHERE record_id = :record_id');
    $stmt->bindParam(':record_id', $record_id);
    $stmt->bindParam(':patient_id', $patient_id);
    $stmt->bindParam(':record_date', $record_date);
    $stmt->bindParam(':record_details', $record_details);
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

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $record_id = filter_var($input['record_id'] ?? null, FILTER_VALIDATE_INT);
    if ($record_id === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid record ID']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('DELETE FROM سجلات_مريض WHERE record_id = :record_id');
    $stmt->bindParam(':record_id', $record_id);
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
    echo json_encode(['error' => 'Method not allowed']);
}