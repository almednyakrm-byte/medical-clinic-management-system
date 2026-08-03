<?php
require_once 'db.php';

// Get user role from session
$userRole = $_SESSION['userRole'];

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON or POST
$inputData = json_decode(file_get_contents('php://input'), true);
if (!$inputData) {
    $inputData = $_POST;
}

// Function to validate and sanitize input data
function validateInput($data) {
    $sanitizedData = array();
    foreach ($data as $key => $value) {
        if (in_array($key, array('id', 'username', 'email', 'role'))) {
            $sanitizedData[$key] = filter_var($value, FILTER_SANITIZE_STRING);
        } elseif (in_array($key, array('phone', 'address'))) {
            $sanitizedData[$key] = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        } else {
            $sanitizedData[$key] = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        }
    }
    return $sanitizedData;
}

// Function to handle CRUD operations
function handleOperation($method, $id = null) {
    global $pdo, $userRole;

    switch ($method) {
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare('SELECT * FROM تتبع WHERE id = :id');
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                $result = $stmt->fetch();
                if ($result) {
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode($result);
                } else {
                    http_response_code(404);
                    echo json_encode(array('error' => 'Not found'));
                }
            } else {
                $stmt = $pdo->prepare('SELECT * FROM تتبع');
                $stmt->execute();
                $results = $stmt->fetchAll();
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($results);
            }
            break;
        case 'POST':
            if ($userRole == 'admin') {
                $inputData = validateInput($inputData);
                $stmt = $pdo->prepare('INSERT INTO تتبع (username, email, role, phone, address) VALUES (:username, :email, :role, :phone, :address)');
                $stmt->bindParam(':username', $inputData['username']);
                $stmt->bindParam(':email', $inputData['email']);
                $stmt->bindParam(':role', $inputData['role']);
                $stmt->bindParam(':phone', $inputData['phone']);
                $stmt->bindParam(':address', $inputData['address']);
                $stmt->execute();
                http_response_code(201);
                header('Content-Type: application/json');
                echo json_encode(array('message' => 'Created successfully'));
            } else {
                http_response_code(403);
                echo json_encode(array('error' => 'Forbidden'));
            }
            break;
        case 'PUT':
            if ($userRole == 'admin') {
                $inputData = validateInput($inputData);
                $stmt = $pdo->prepare('UPDATE تتبع SET username = :username, email = :email, role = :role, phone = :phone, address = :address WHERE id = :id');
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':username', $inputData['username']);
                $stmt->bindParam(':email', $inputData['email']);
                $stmt->bindParam(':role', $inputData['role']);
                $stmt->bindParam(':phone', $inputData['phone']);
                $stmt->bindParam(':address', $inputData['address']);
                $stmt->execute();
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode(array('message' => 'Updated successfully'));
            } else {
                http_response_code(403);
                echo json_encode(array('error' => 'Forbidden'));
            }
            break;
        case 'DELETE':
            if ($userRole == 'admin') {
                $stmt = $pdo->prepare('DELETE FROM تتبع WHERE id = :id');
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode(array('message' => 'Deleted successfully'));
            } else {
                http_response_code(403);
                echo json_encode(array('error' => 'Forbidden'));
            }
            break;
    }
}

// Handle request
$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? $_GET['id'] : null;
handleOperation($method, $id);