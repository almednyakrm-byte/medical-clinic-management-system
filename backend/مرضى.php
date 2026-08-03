<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (isset($_SESSION['role']) && $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        // Prepare SQL query to select all patients
        $stmt = $pdo->prepare('SELECT * FROM مرضى');
        $stmt->execute();
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return patients in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($patients);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Validate input data
        if (!isset($input['اسم_المرضى']) || !isset($input['تاريخ_الميلاد']) || !isset($input['العنوان'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
        
        // Sanitize input data
        $name = filter_var($input['اسم_المرضى'], FILTER_SANITIZE_STRING);
        $birthdate = filter_var($input['تاريخ_الميلاد'], FILTER_SANITIZE_STRING);
        $address = filter_var($input['العنوان'], FILTER_SANITIZE_STRING);
        
        // Prepare SQL query to insert patient
        $stmt = $pdo->prepare('INSERT INTO مرضى (اسم_المرضى, تاريخ_الميلاد, العنوان) VALUES (:name, :birthdate, :address)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':address', $address);
        $stmt->execute();
        
        // Return patient ID in JSON format
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $pdo->lastInsertId()));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    try {
        // Validate input data
        if (!isset($input['id']) || !isset($input['اسم_المرضى']) || !isset($input['تاريخ_الميلاد']) || !isset($input['العنوان'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
        
        // Sanitize input data
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($input['اسم_المرضى'], FILTER_SANITIZE_STRING);
        $birthdate = filter_var($input['تاريخ_الميلاد'], FILTER_SANITIZE_STRING);
        $address = filter_var($input['العنوان'], FILTER_SANITIZE_STRING);
        
        // Prepare SQL query to update patient
        $stmt = $pdo->prepare('UPDATE مرضى SET اسم_المرضى = :name, تاريخ_الميلاد = :birthdate, العنوان = :address WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':address', $address);
        $stmt->execute();
        
        // Return success message in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Patient updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    try {
        // Validate input data
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
        
        // Sanitize input data
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
        
        // Prepare SQL query to delete patient
        $stmt = $pdo->prepare('DELETE FROM مرضى WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Return success message in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Patient deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}