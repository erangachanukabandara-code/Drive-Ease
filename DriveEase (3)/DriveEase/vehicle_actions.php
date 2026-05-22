<?php
session_start();
require 'db.php';

// Ensure the request is coming from a logged-in owner
if (!isset($_SESSION['owner_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$owner_id = $_SESSION['owner_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action === 'add' || $action === 'update') {
        $v_id = isset($_POST['vehical_id']) ? $conn->real_escape_string($_POST['vehical_id']) : '';
        $v_num = $conn->real_escape_string($_POST['vehical_number']);
        $v_type = $conn->real_escape_string($_POST['vehical_type']);
        $v_model = $conn->real_escape_string($_POST['vehical_model']);
        $v_engine = $conn->real_escape_string($_POST['engine_capacity']);
        $v_price = $conn->real_escape_string($_POST['price_per_day']);
        $v_details = $conn->real_escape_string($_POST['additional_details']);
        
        $image_path = "";
        
        // Handle Image Upload if provided
        if (isset($_FILES['vehical_image']) && $_FILES['vehical_image']['error'] == 0) {
            $upload_dir = 'uploads/vehicles/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $image_path = $upload_dir . time() . '_' . basename($_FILES['vehical_image']['name']);
            move_uploaded_file($_FILES['vehical_image']['tmp_name'], $image_path);
        }

        if ($action === 'add') {
            if (empty($image_path)) {
                echo json_encode(['status' => 'error', 'message' => 'Image is required for adding.']);
                exit;
            }
            $sql = "INSERT INTO owner_vehicals (owner_id, vehical_number, vehical_type, vehical_model, engine_capacity, price_per_day, additional_details, vehical_image) 
                    VALUES ('$owner_id', '$v_num', '$v_type', '$v_model', '$v_engine', '$v_price', '$v_details', '$image_path')";
        } else if ($action === 'update') {
            if (empty($v_id)) {
                echo json_encode(['status' => 'error', 'message' => 'Vehicle ID required for update.']);
                exit;
            }
            // Update query setup (only update image if a new one was uploaded)
            $imgUpdateStr = !empty($image_path) ? ", vehical_image='$image_path'" : "";
            $sql = "UPDATE owner_vehicals SET vehical_number='$v_num', vehical_type='$v_type', vehical_model='$v_model', engine_capacity='$v_engine', price_per_day='$v_price', additional_details='$v_details' $imgUpdateStr WHERE id='$v_id' AND owner_id='$owner_id'";
        }

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error.']);
        }
    }

    if ($action === 'delete') {
        $v_id = $conn->real_escape_string($_POST['vehical_id']);
        $sql = "DELETE FROM owner_vehicals WHERE id='$v_id' AND owner_id='$owner_id'";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Delete failed.']);
        }
    }
}

// Fetch Logic (GET Request)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'fetch') {
    $result = $conn->query("SELECT * FROM owner_vehicals WHERE owner_id='$owner_id' ORDER BY id DESC");
    $vehicles = [];
    while ($row = $result->fetch_assoc()) {
        $vehicles[] = $row;
    }
    echo json_encode($vehicles);
}
?>