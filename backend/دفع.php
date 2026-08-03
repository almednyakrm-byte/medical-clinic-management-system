<?php

require_once 'db.php';

// Get user role and check if user is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin' && $_SESSION['role'] != 'user') {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate and sanitize input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM دفع WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();

    // Fetch data
    $data = $stmt->fetch();

    // Check if data exists
    if ($data) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input
    if (!isset($input['name']) || !isset($input['amount'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO دفع (name, amount) VALUES (:name, :amount)');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':amount', $input['amount']);
    $stmt->execute();

    // Get last inserted ID
    $id = $pdo->lastInsertId();

    // Return success response
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $id));
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate and sanitize input
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['amount'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE دفع SET name = :name, amount = :amount WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':amount', $input['amount']);
    $stmt->execute();

    // Check if update was successful
    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Updated successfully'));
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate and sanitize input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM دفع WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();

    // Check if delete was successful
    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Deleted successfully'));
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
}