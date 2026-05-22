<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Admin Authentication Check
    if ($email === 'admin@driveease.lk' && $password === 'Admin@1234') {
        $_SESSION['user_role'] = 'admin';
        $_SESSION['email'] = $email;
        echo json_encode(['status' => 'success', 'redirect' => 'admin_dashboard.php']);
        exit;
    }

    // Customer Authentication Check
    $sql = "SELECT id, full_name, password FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_role'] = 'customer';
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            
            echo json_encode(['status' => 'success', 'redirect' => 'customer_dashboard.php']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Incorrect password!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No account found with that email!']);
    }
}
?>