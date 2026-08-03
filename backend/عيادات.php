<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

// Define allowed roles for each operation
$allowedRoles = array(
    'GET' => array('user', 'admin'),
    'POST' => array('user', 'admin'),
    'PUT' => array('admin'),
    'DELETE' => array('admin')
);

// Check if user has permission to perform the requested operation
if (!in_array($_SESSION['role'], $allowedRoles[$_SERVER['REQUEST_METHOD']])) {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Validate input data
if (isset($input['id'])) {
    $input['id'] = (int) $input['id'];
    if ($input['id'] <= 0) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid ID'));
        exit;
    }
}

// Process the request
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Get all clinics
        $stmt = $pdo->prepare('SELECT * FROM عيادات');
        $stmt->execute();
        $clinics = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode($clinics);
        break;
    case 'POST':
        // Create a new clinic
        $requiredFields = array('name', 'address', 'phone');
        foreach ($requiredFields as $field) {
            if (!isset($input[$field])) {
                http_response_code(400);
                echo json_encode(array('error' => 'Missing required field: ' . $field));
                exit;
            }
        }
        $stmt = $pdo->prepare('INSERT INTO عيادات (name, address, phone) VALUES (:name, :address, :phone)');
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':address', $input['address']);
        $stmt->bindParam(':phone', $input['phone']);
        $stmt->execute();
        http_response_code(201);
        echo json_encode(array('message' => 'Clinic created successfully'));
        break;
    case 'PUT':
        // Update an existing clinic
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required field: ID'));
            exit;
        }
        $requiredFields = array('name', 'address', 'phone');
        foreach ($requiredFields as $field) {
            if (!isset($input[$field])) {
                http_response_code(400);
                echo json_encode(array('error' => 'Missing required field: ' . $field));
                exit;
            }
        }
        $stmt = $pdo->prepare('UPDATE عيادات SET name = :name, address = :address, phone = :phone WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':address', $input['address']);
        $stmt->bindParam(':phone', $input['phone']);
        $stmt->execute();
        http_response_code(200);
        echo json_encode(array('message' => 'Clinic updated successfully'));
        break;
    case 'DELETE':
        // Delete a clinic
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required field: ID'));
            exit;
        }
        $stmt = $pdo->prepare('DELETE FROM عيادات WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        http_response_code(200);
        echo json_encode(array('message' => 'Clinic deleted successfully'));
        break;
    default:
        http_response_code(405);
        echo json_encode(array('error' => 'Method not allowed'));
        break;
}