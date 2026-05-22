<?php
// Start session BEFORE any output
session_start();

// Force the server to return strictly JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Hardcoded check based on your exact requirements
    if ($email === 'admin.driverease.lk' && $password === 'Admin@DriverEase') {
        
        // Set master session variables
        $_SESSION['user_role'] = 'admin';
        $_SESSION['admin_logged_in'] = true;
        
        echo json_encode([
            'status' => 'success', 
            'redirect' => 'admin_portal.php'
        ]);
        
    } else {
        // If credentials do not match
        echo json_encode([
            'status' => 'error', 
            'message' => 'Invalid admin credentials provided.'
        ]);
    }
    exit;
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Invalid request method.'
    ]);
    exit;
}
?>