<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = [
    '/muaadid' => [
        'GET' => function() {
            global $pdo;
            $stmt = $pdo->prepare('SELECT * FROM muaadid');
            $stmt->execute();
            $muaadid = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($muaadid);
        },
        'POST' => function() {
            global $pdo;
            // Validate input
            if (!isset($input['name']) || !isset($input['date']) || !isset($input['time'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input']);
                exit;
            }
            // Sanitize input
            $name = htmlspecialchars($input['name']);
            $date = htmlspecialchars($input['date']);
            $time = htmlspecialchars($input['time']);
            // Insert data
            $stmt = $pdo->prepare('INSERT INTO muaadid (name, date, time) VALUES (:name, :date, :time)');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':time', $time);
            $stmt->execute();
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Muaadid created successfully']);
        }
    ],
    '/muaadid/{id}' => [
        'GET' => function($id) {
            global $pdo;
            // Validate ID
            if (!ctype_digit($id)) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid ID']);
                exit;
            }
            // Get ID
            $id = intval($id);
            // Check if user is admin
            if ($_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }
            // Select data
            $stmt = $pdo->prepare('SELECT * FROM muaadid WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $muaadid = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$muaadid) {
                http_response_code(404);
                echo json_encode(['error' => 'Not found']);
                exit;
            }
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($muaadid);
        },
        'PUT' => function($id) {
            global $pdo;
            // Validate ID
            if (!ctype_digit($id)) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid ID']);
                exit;
            }
            // Get ID
            $id = intval($id);
            // Check if user is admin
            if ($_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }
            // Validate input
            if (!isset($input['name']) || !isset($input['date']) || !isset($input['time'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input']);
                exit;
            }
            // Sanitize input
            $name = htmlspecialchars($input['name']);
            $date = htmlspecialchars($input['date']);
            $time = htmlspecialchars($input['time']);
            // Update data
            $stmt = $pdo->prepare('UPDATE muaadid SET name = :name, date = :date, time = :time WHERE id = :id');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':time', $time);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Muaadid updated successfully']);
        },
        'DELETE' => function($id) {
            global $pdo;
            // Validate ID
            if (!ctype_digit($id)) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid ID']);
                exit;
            }
            // Get ID
            $id = intval($id);
            // Check if user is admin
            if ($_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }
            // Delete data
            $stmt = $pdo->prepare('DELETE FROM muaadid WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Muaadid deleted successfully']);
        }
    ]
];

// Get route
$route = explode('/', $_SERVER['REQUEST_URI']);
array_shift($route);
array_shift($route);
$route = implode('/', $route);

// Check if route exists
if (isset($routes[$route])) {
    // Get method
    $method = $_SERVER['REQUEST_METHOD'];
    if (isset($routes[$route][$method])) {
        // Call route function
        $routes[$route][$method]();
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}