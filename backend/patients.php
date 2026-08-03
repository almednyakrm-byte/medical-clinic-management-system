<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize patient data
$patientData = json_decode(file_get_contents('php://input'), true);

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// GET all patients
if ($method == 'GET') {
    // Validate user role for admin-only access
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query to select all patients
    $stmt = $pdo->prepare('SELECT * FROM patients');
    $stmt->execute();

    // Fetch all patients
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Set HTTP response status code and Content-Type header
    http_response_code(200);
    header('Content-Type: application/json');

    // Output patients in JSON format
    echo json_encode($patients);
}

// POST new patient
elseif ($method == 'POST') {
    // Validate user role for admin-only access
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Forbidden']);
        exit;
    }

    // Validate patient data
    if (!isset($patientData['name']) || !isset($patientData['email']) || !isset($patientData['phone'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Invalid request data']);
        exit;
    }

    // Sanitize patient data
    $name = filter_var($patientData['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($patientData['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($patientData['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare SQL query to insert new patient
    $stmt = $pdo->prepare('INSERT INTO patients (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);

    // Execute SQL query
    if ($stmt->execute()) {
        // Set HTTP response status code and Content-Type header
        http_response_code(201);
        header('Content-Type: application/json');

        // Output created patient ID
        echo json_encode(['id' => $pdo->lastInsertId()]);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Internal Server Error']);
    }
}

// PUT update patient
elseif ($method == 'PUT') {
    // Validate user role for admin-only access
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Forbidden']);
        exit;
    }

    // Validate patient ID
    if (!isset($patientData['id'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Invalid request data']);
        exit;
    }

    // Validate patient data
    if (!isset($patientData['name']) || !isset($patientData['email']) || !isset($patientData['phone'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Invalid request data']);
        exit;
    }

    // Sanitize patient data
    $id = filter_var($patientData['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($patientData['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($patientData['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($patientData['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare SQL query to update patient
    $stmt = $pdo->prepare('UPDATE patients SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);

    // Execute SQL query
    if ($stmt->execute()) {
        // Set HTTP response status code and Content-Type header
        http_response_code(200);
        header('Content-Type: application/json');

        // Output updated patient data
        echo json_encode(['message' => 'Patient updated successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Internal Server Error']);
    }
}

// DELETE patient
elseif ($method == 'DELETE') {
    // Validate user role for admin-only access
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Forbidden']);
        exit;
    }

    // Validate patient ID
    if (!isset($patientData['id'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Invalid request data']);
        exit;
    }

    // Sanitize patient ID
    $id = filter_var($patientData['id'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare SQL query to delete patient
    $stmt = $pdo->prepare('DELETE FROM patients WHERE id = :id');
    $stmt->bindParam(':id', $id);

    // Execute SQL query
    if ($stmt->execute()) {
        // Set HTTP response status code and Content-Type header
        http_response_code(200);
        header('Content-Type: application/json');

        // Output deleted patient message
        echo json_encode(['message' => 'Patient deleted successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Internal Server Error']);
    }
}

// Invalid request method
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Method Not Allowed']);
}