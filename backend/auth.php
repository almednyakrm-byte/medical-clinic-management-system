<?php
// Start the session to handle user authentication
session_start();

// Import the database connection script
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, send a JSON response with their user data
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $response = array(
        'status' => 'logged_in',
        'user_id' => $user_id,
        'username' => $username
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Handle the login request
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if the username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Sanitize the input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Prepare the SQL query to select the user
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        // Check if the user exists and the password is correct
        if ($user && password_verify($password, $user['password'])) {
            // If the user is valid, log them in and send a JSON response
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $response = array(
                'status' => 'logged_in',
                'user_id' => $user['id'],
                'username' => $user['username']
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } else {
            // If the user is not valid, send a JSON response with an error message
            $response = array(
                'status' => 'error',
                'message' => 'Invalid username or password'
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    } else {
        // If the username or password is not set, send a JSON response with an error message
        $response = array(
            'status' => 'error',
            'message' => 'Username and password are required'
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

// Handle the register request
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if the username, email, and password are set
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize the input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Check if the username and email are already taken
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        // Check if the user already exists
        if ($user) {
            // If the user already exists, send a JSON response with an error message
            $response = array(
                'status' => 'error',
                'message' => 'Username or email already taken'
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query to insert the new user
        $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        // Send a JSON response with a success message
        $response = array(
            'status' => 'success',
            'message' => 'User created successfully'
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } else {
        // If the username, email, or password is not set, send a JSON response with an error message
        $response = array(
            'status' => 'error',
            'message' => 'Username, email, and password are required'
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

// Handle the logout request
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy the session and send a JSON response
    session_destroy();
    $response = array(
        'status' => 'logged_out'
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}