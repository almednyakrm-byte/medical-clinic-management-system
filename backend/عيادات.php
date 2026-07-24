<?php

require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $clinicID = isset($inputData['clinicID']) ? intval($inputData['clinicID']) : 0;

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('SELECT * FROM عيادات WHERE clinicID = :clinicID');
        $stmt->bindParam(':clinicID', $clinicID);
        $stmt->execute();

        // Fetch and return data
        $clinics = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($clinics);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $clinicName = isset($inputData['clinicName']) ? trim($inputData['clinicName']) : '';
    $clinicAddress = isset($inputData['clinicAddress']) ? trim($inputData['clinicAddress']) : '';

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('INSERT INTO عيادات (clinicName, clinicAddress) VALUES (:clinicName, :clinicAddress)');
        $stmt->bindParam(':clinicName', $clinicName);
        $stmt->bindParam(':clinicAddress', $clinicAddress);
        $stmt->execute();

        // Return inserted ID
        $lastID = $pdo->lastInsertId();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('clinicID' => $lastID));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate and sanitize input
    $clinicID = isset($inputData['clinicID']) ? intval($inputData['clinicID']) : 0;
    $clinicName = isset($inputData['clinicName']) ? trim($inputData['clinicName']) : '';
    $clinicAddress = isset($inputData['clinicAddress']) ? trim($inputData['clinicAddress']) : '';

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('UPDATE عيادات SET clinicName = :clinicName, clinicAddress = :clinicAddress WHERE clinicID = :clinicID');
        $stmt->bindParam(':clinicID', $clinicID);
        $stmt->bindParam(':clinicName', $clinicName);
        $stmt->bindParam(':clinicAddress', $clinicAddress);
        $stmt->execute();

        // Return updated count
        $updatedCount = $stmt->rowCount();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('updatedCount' => $updatedCount));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input
    $clinicID = isset($inputData['clinicID']) ? intval($inputData['clinicID']) : 0;

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('DELETE FROM عيادات WHERE clinicID = :clinicID');
        $stmt->bindParam(':clinicID', $clinicID);
        $stmt->execute();

        // Return deleted count
        $deletedCount = $stmt->rowCount();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('deletedCount' => $deletedCount));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle invalid request method
else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
}