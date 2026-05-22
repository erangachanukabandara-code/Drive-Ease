<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $address = $conn->real_escape_string($_POST['address']);
    $nic = $conn->real_escape_string($_POST['nic']);
    $nationality = $conn->real_escape_string($_POST['nationality']);
    $gender = $conn->real_escape_string($_POST['gender']);
    
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if email already exists in owner_accounts
    $check_email = $conn->query("SELECT id FROM owner_accounts WHERE email='$email'");
    if ($check_email->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'This Owner Email is already registered!']);
        exit;
    }

    $image_name = $_FILES['profile_image']['name'];
    $image_tmp = $_FILES['profile_image']['tmp_name'];
    
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Prefix with "owner_" to keep folder organized
    $target_file = $upload_dir . 'owner_' . time() . '_' . basename($image_name);

    if (move_uploaded_file($image_tmp, $target_file)) {
        $sql = "INSERT INTO owner_accounts (full_name, email, mobile, address, nic, gender, nationality, password, profile_image) 
                VALUES ('$full_name', '$email', '$mobile', '$address', '$nic', '$gender', '$nationality', '$password', '$target_file')";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error during insertion!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload profile image.']);
    }
}
?>