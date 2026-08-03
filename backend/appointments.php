<?php
// Import database connection
require_once 'db.php';

// Initialize database connection
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);

// Function to validate user role
function validateUserRole($role) {
    // For this example, assume we have a session variable 'user_role'
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $role) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
}

// Function to validate user login
function validateUserLogin() {
    // For this example, assume we have a session variable 'logged_in'
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    validateUserLogin();
    $stmt = $pdo->prepare('SELECT * FROM appointments');
    $stmt->execute();
    $appointments = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($appointments);
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validateUserLogin();
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['title']) || empty($data['date']) || empty($data['time'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Please fill in all fields']);
        exit;
    }
    $stmt = $pdo->prepare('INSERT INTO appointments (title, date, time) VALUES (:title, :date, :time)');
    $stmt->bindParam(':title', $data['title']);
    $stmt->bindParam(':date', $data['date']);
    $stmt->bindParam(':time', $data['time']);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Appointment created successfully']);
}

// Handle PUT requests
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    validateUserRole('admin');
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['id']) || empty($data['title']) || empty($data['date']) || empty($data['time'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Please fill in all fields']);
        exit;
    }
    $stmt = $pdo->prepare('UPDATE appointments SET title = :title, date = :date, time = :time WHERE id = :id');
    $stmt->bindParam(':id', $data['id']);
    $stmt->bindParam(':title', $data['title']);
    $stmt->bindParam(':date', $data['date']);
    $stmt->bindParam(':time', $data['time']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Appointment updated successfully']);
}

// Handle DELETE requests
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    validateUserRole('admin');
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Please provide an appointment ID']);
        exit;
    }
    $stmt = $pdo->prepare('DELETE FROM appointments WHERE id = :id');
    $stmt->bindParam(':id', $data['id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Appointment deleted successfully']);
}