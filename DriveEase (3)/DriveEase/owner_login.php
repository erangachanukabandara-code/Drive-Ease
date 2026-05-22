<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT id, full_name, password FROM owner_accounts WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $owner = $result->fetch_assoc();
        
        if (password_verify($password, $owner['password'])) {
            // Set session variables specifically for owners
            $_SESSION['user_role'] = 'owner';
            $_SESSION['owner_id'] = $owner['id'];
            $_SESSION['owner_name'] = $owner['full_name'];
            
            echo json_encode(['status' => 'success', 'redirect' => 'owner_dashboard.php']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Incorrect password!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No owner account found with that email!']);
    }
}
?>