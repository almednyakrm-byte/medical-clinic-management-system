<?php
require_once 'db.php';

// Get the user role from the session
$userRole = $_SESSION['userRole'];

// Check if the user is logged in
if (!$userRole) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET requests
if ($method === 'GET') {
    // Get the schedule ID from the URL query string
    $scheduleId = $_GET['id'] ?? null;

    // Check if the user is an admin to allow editing/deleting
    if ($userRole !== 'admin' && ($scheduleId || $scheduleId === 0)) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all schedules or a specific schedule
    if ($scheduleId) {
        $stmt = $pdo->prepare('SELECT * FROM مواعيد WHERE id = :id');
        $stmt->bindParam(':id', $scheduleId);
        $stmt->execute();
        $schedule = $stmt->fetch();
        if (!$schedule) {
            http_response_code(404);
            echo json_encode(array('error' => 'Not Found'));
            exit;
        }
        echo json_encode($schedule);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM مواعيد');
        $stmt->execute();
        $schedules = $stmt->fetchAll();
        echo json_encode($schedules);
    }
} elseif ($method === 'POST') {
    // Get the schedule data from the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate the schedule data
    if (!isset($data['title']) || !isset($data['date']) || !isset($data['time'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Bad Request'));
        exit;
    }

    // Sanitize the schedule data
    $title = htmlspecialchars($data['title']);
    $date = htmlspecialchars($data['date']);
    $time = htmlspecialchars($data['time']);

    // Insert the schedule into the database
    $stmt = $pdo->prepare('INSERT INTO مواعيد (title, date, time) VALUES (:title, :date, :time)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    $stmt->execute();

    // Return the newly inserted schedule
    $schedule = $pdo->lastInsertId();
    echo json_encode(array('id' => $schedule, 'title' => $title, 'date' => $date, 'time' => $time));
} elseif ($method === 'PUT') {
    // Get the schedule ID from the URL query string
    $scheduleId = $_GET['id'];

    // Check if the user is an admin to allow editing
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get the schedule data from the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate the schedule data
    if (!isset($data['title']) || !isset($data['date']) || !isset($data['time'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Bad Request'));
        exit;
    }

    // Sanitize the schedule data
    $title = htmlspecialchars($data['title']);
    $date = htmlspecialchars($data['date']);
    $time = htmlspecialchars($data['time']);

    // Update the schedule in the database
    $stmt = $pdo->prepare('UPDATE مواعيد SET title = :title, date = :date, time = :time WHERE id = :id');
    $stmt->bindParam(':id', $scheduleId);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    $stmt->execute();

    // Return the updated schedule
    $schedule = $pdo->prepare('SELECT * FROM مواعيد WHERE id = :id');
    $schedule->bindParam(':id', $scheduleId);
    $schedule->execute();
    $schedule = $schedule->fetch();
    echo json_encode($schedule);
} elseif ($method === 'DELETE') {
    // Get the schedule ID from the URL query string
    $scheduleId = $_GET['id'];

    // Check if the user is an admin to allow deleting
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete the schedule from the database
    $stmt = $pdo->prepare('DELETE FROM مواعيد WHERE id = :id');
    $stmt->bindParam(':id', $scheduleId);
    $stmt->execute();

    // Return a success message
    echo json_encode(array('message' => 'Schedule deleted successfully'));
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
}
http_response_code(200);
header('Content-Type: application/json');