<?php
session_start();
header('Content-Type: application/json'); // Crucial for JS parsing
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $conn->real_escape_string($_POST['full_name'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $mobile = $conn->real_escape_string($_POST['mobile'] ?? '');
    $license_number = $conn->real_escape_string($_POST['license_number'] ?? '');
    $password = password_hash($_POST['password'] ?? '', PASSWORD_BCRYPT);

    $check_email = $conn->query("SELECT id FROM drivers WHERE email='$email'");
    if ($check_email && $check_email->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'This Email is already registered!']);
        exit;
    }

    $sql = "INSERT INTO drivers (full_name, email, mobile, license_number, password) 
            VALUES ('$full_name', '$email', '$mobile', '$license_number', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error during insertion!']);
    }
    exit;
}
echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
?>