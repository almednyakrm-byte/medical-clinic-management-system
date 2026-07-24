<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method === 'GET') {
    // Get all patients
    $stmt = $pdo->prepare('SELECT * FROM مرضى');
    $stmt->execute();
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($patients);
}

// Handle POST request
elseif ($method === 'POST') {
    // Get patient data from request body
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate patient data
    if (!isset($data['اسم_المرضى']) || !isset($data['تاريخ_الميلاد']) || !isset($data['العمر'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid patient data']);
        exit;
    }
    
    // Sanitize patient data
    $name = filter_var($data['اسم_المرضى'], FILTER_SANITIZE_STRING);
    $birthDate = filter_var($data['تاريخ_الميلاد'], FILTER_SANITIZE_STRING);
    $age = filter_var($data['العمر'], FILTER_SANITIZE_NUMBER_INT);
    
    // Insert patient into database
    $stmt = $pdo->prepare('INSERT INTO مرضى (اسم_المرضى, تاريخ_الميلاد, العمر) VALUES (:name, :birthDate, :age)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':birthDate', $birthDate);
    $stmt->bindParam(':age', $age);
    $stmt->execute();
    
    // Return created patient data
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Patient created successfully']);
}

// Handle PUT request
elseif ($method === 'PUT') {
    // Get patient ID from URL
    $patientID = $_GET['id'];
    
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    // Get patient data from request body
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate patient data
    if (!isset($data['اسم_المرضى']) || !isset($data['تاريخ_الميلاد']) || !isset($data['العمر'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid patient data']);
        exit;
    }
    
    // Sanitize patient data
    $name = filter_var($data['اسم_المرضى'], FILTER_SANITIZE_STRING);
    $birthDate = filter_var($data['تاريخ_الميلاد'], FILTER_SANITIZE_STRING);
    $age = filter_var($data['العمر'], FILTER_SANITIZE_NUMBER_INT);
    
    // Update patient in database
    $stmt = $pdo->prepare('UPDATE مرضى SET اسم_المرضى = :name, تاريخ_الميلاد = :birthDate, العمر = :age WHERE id = :id');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':birthDate', $birthDate);
    $stmt->bindParam(':age', $age);
    $stmt->bindParam(':id', $patientID);
    $stmt->execute();
    
    // Return updated patient data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Patient updated successfully']);
}

// Handle DELETE request
elseif ($method === 'DELETE') {
    // Get patient ID from URL
    $patientID = $_GET['id'];
    
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    // Delete patient from database
    $stmt = $pdo->prepare('DELETE FROM مرضى WHERE id = :id');
    $stmt->bindParam(':id', $patientID);
    $stmt->execute();
    
    // Return deleted patient data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Patient deleted successfully']);
}