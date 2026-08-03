<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['id'])) {
    // Get service by ID
    $stmt = $pdo->prepare('SELECT * FROM services WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $service = $stmt->fetch();
    if ($service) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($service);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Service not found'));
    }
} elseif (isset($_GET['all'])) {
    // Get all services
    $stmt = $pdo->prepare('SELECT * FROM services');
    $stmt->execute();
    $services = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($services);
} else {
    // Handle POST, PUT, DELETE requests
    if (isset($inputData['id'])) {
        // Handle PUT request
        if ($_SESSION['role'] == 'admin') {
            // Update service
            $stmt = $pdo->prepare('UPDATE services SET name = :name, description = :description WHERE id = :id');
            $stmt->bindParam(':id', $inputData['id']);
            $stmt->bindParam(':name', $inputData['name']);
            $stmt->bindParam(':description', $inputData['description']);
            $stmt->execute();
            http_response_code(200);
            echo json_encode(array('message' => 'Service updated successfully'));
        } else {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
        }
    } elseif (isset($inputData['name']) && isset($inputData['description'])) {
        // Handle POST request
        // Insert new service
        $stmt = $pdo->prepare('INSERT INTO services (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $inputData['name']);
        $stmt->bindParam(':description', $inputData['description']);
        $stmt->execute();
        http_response_code(201);
        echo json_encode(array('message' => 'Service created successfully'));
    } elseif (isset($inputData['id'])) {
        // Handle DELETE request
        if ($_SESSION['role'] == 'admin') {
            // Delete service
            $stmt = $pdo->prepare('DELETE FROM services WHERE id = :id');
            $stmt->bindParam(':id', $inputData['id']);
            $stmt->execute();
            http_response_code(200);
            echo json_encode(array('message' => 'Service deleted successfully'));
        } else {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
        }
    } else {
        http_response_code(400);
        echo json_encode(array('error' => 'Bad request'));
    }
}