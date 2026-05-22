<?php
session_start();
header('Content-Type: application/json'); // Crucial for JS parsing
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $sql = "SELECT id, full_name, password FROM drivers WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $driver = $result->fetch_assoc();
        
        if (password_verify($password, $driver['password'])) {
            $_SESSION['user_role'] = 'driver';
            $_SESSION['driver_id'] = $driver['id'];
            $_SESSION['driver_name'] = $driver['full_name'];
            
            echo json_encode(['status' => 'success', 'redirect' => 'driver_dashboard.php']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Incorrect password!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No driver account found with that email!']);
    }
    exit;
}
echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
?>