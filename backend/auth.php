<?php
// Start the session to handle user authentication
session_start();

// Import the database connection
require_once 'db.php';

// Check if the request method is GET or POST
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check the current session status
    if (isset($_SESSION['user_id'])) {
        // User is logged in, return the user ID
        $response = array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']);
    } else {
        // User is not logged in, return a not logged in status
        $response = array('status' => 'not_logged_in');
    }
    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check the action parameter
    if (isset($_POST['action'])) {
        // Handle the login action
        if ($_POST['action'] === 'login') {
            // Check if the username and password fields are set
            if (isset($_POST['username']) && isset($_POST['password'])) {
                // Prepare the SQL query to select the user by username
                $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
                // Bind the username parameter
                $stmt->bind_param('s', $_POST['username']);
                // Execute the query
                $stmt->execute();
                // Get the result
                $result = $stmt->get_result();
                // Check if a user was found
                if ($result->num_rows === 1) {
                    // Get the user data
                    $user = $result->fetch_assoc();
                    // Check if the password is correct
                    if (password_verify($_POST['password'], $user['password'])) {
                        // Password is correct, log the user in
                        $_SESSION['user_id'] = $user['id'];
                        // Send a success response
                        $response = array('status' => 'success', 'message' => 'Logged in successfully');
                    } else {
                        // Password is incorrect, send an error response
                        $response = array('status' => 'error', 'message' => 'Invalid password');
                    }
                } else {
                    // No user was found, send an error response
                    $response = array('status' => 'error', 'message' => 'User not found');
                }
            } else {
                // Username or password field is missing, send an error response
                $response = array('status' => 'error', 'message' => 'Missing fields');
            }
        } 
        // Handle the register action
        elseif ($_POST['action'] === 'register') {
            // Check if the username, email, and password fields are set
            if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
                // Prepare the SQL query to insert a new user
                $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
                // Hash the password
                $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                // Bind the parameters
                $stmt->bind_param('sss', $_POST['username'], $_POST['email'], $password_hash);
                // Execute the query
                if ($stmt->execute()) {
                    // Send a success response
                    $response = array('status' => 'success', 'message' => 'Registered successfully');
                } else {
                    // Send an error response
                    $response = array('status' => 'error', 'message' => 'Failed to register');
                }
            } else {
                // Username, email, or password field is missing, send an error response
                $response = array('status' => 'error', 'message' => 'Missing fields');
            }
        } 
        // Handle the logout action
        elseif ($_POST['action'] === 'logout') {
            // Unset the user ID from the session
            unset($_SESSION['user_id']);
            // Send a success response
            $response = array('status' => 'success', 'message' => 'Logged out successfully');
        }
    } else {
        // Action parameter is missing, send an error response
        $response = array('status' => 'error', 'message' => 'Missing action');
    }
    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Invalid request method, send an error response
    $response = array('status' => 'error', 'message' => 'Invalid request method');
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Handle the logout action when the user closes the browser
if (isset($_GET['logout'])) {
    // Unset the user ID from the session
    unset($_SESSION['user_id']);
    // Destroy the session
    session_destroy();
    // Redirect to the login page
    header('Location: login.php');
    exit;
}