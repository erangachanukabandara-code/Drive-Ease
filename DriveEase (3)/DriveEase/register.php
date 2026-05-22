<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $nic = $conn->real_escape_string($_POST['nic']);
    $gender = $conn->real_escape_string($_POST['gender']);
    
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $check_email = $conn->query("SELECT id FROM users WHERE email='$email'");
    if ($check_email->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email already registered!']);
        exit;
    }

    $image_name = $_FILES['profile_image']['name'];
    $image_tmp = $_FILES['profile_image']['tmp_name'];
    
    // Ensure the uploads directory exists
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $target_file = $upload_dir . time() . '_' . basename($image_name); // Added time() to prevent overwriting

    if (move_uploaded_file($image_tmp, $target_file)) {
        $sql = "INSERT INTO users (full_name, email, address, mobile, nic, gender, password, profile_image) 
                VALUES ('$full_name', '$email', '$address', '$mobile', '$nic', '$gender', '$password', '$target_file')";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error during insertion!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload profile image. Check folder permissions.']);
    }
}
?>