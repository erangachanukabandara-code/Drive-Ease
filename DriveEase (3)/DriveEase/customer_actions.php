<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit;
}

$customer_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// 1. Fetch All Dashboard Data
if ($action === 'fetchData') {
    $data = ['vehicles' => [], 'profile' => [], 'bookings' => []];
    
    // Fetch Profile
    $res = $conn->query("SELECT * FROM users WHERE id='$customer_id'");
    if ($res && $res->num_rows > 0) $data['profile'] = $res->fetch_assoc();
    
    // Fetch Vehicles
    $res = $conn->query("SELECT * FROM owner_vehicals ORDER BY id DESC");
    if ($res && $res->num_rows > 0) {
        while($row = $res->fetch_assoc()) $data['vehicles'][] = $row;
    }
    
    // Fetch Customer's Bookings
    // Use the customer's full name to fetch their specific bookings
    $customer_name = $data['profile']['full_name']; 
    $res = $conn->query("SELECT * FROM bookings WHERE customer_name='$customer_name' ORDER BY id DESC");
    if ($res && $res->num_rows > 0) {
        while($row = $res->fetch_assoc()) $data['bookings'][] = $row;
    }
    
    echo json_encode(['status' => 'success', 'data' => $data]);
    exit;
}

// 2. Update Profile
if ($action === 'updateProfile') {
    $name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $nic = $conn->real_escape_string($_POST['nic']);
    $gender = $conn->real_escape_string($_POST['gender']);
    
    $updateStr = "full_name='$name', email='$email', address='$address', mobile='$mobile', nic='$nic', gender='$gender'";
    
    // Handle Password Update (if provided)
    if (!empty($_POST['password'])) {
        $hashed_pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $updateStr .= ", password='$hashed_pass'";
    }

    // Handle Profile Image Upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $upload_dir = 'uploads/customers/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $image_path = $upload_dir . time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", basename($_FILES['profile_image']['name']));
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $image_path)) {
            $updateStr .= ", profile_image='$image_path'";
        }
    }

    $sql = "UPDATE users SET $updateStr WHERE id='$customer_id'";
    if ($conn->query($sql)) {
        $_SESSION['full_name'] = $name; // Update session name
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error.']);
    }
    exit;
}

// 3. Process Booking
if ($action === 'processBooking') {
    // Generate a random 5-character booking number (e.g., B-A1X9Z)
    $booking_number = 'B-' . strtoupper(substr(md5(uniqid()), 0, 5));
    
    $c_name = $conn->real_escape_string($_POST['customer_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $nic = $conn->real_escape_string($_POST['nic']);
    $v_type = $conn->real_escape_string($_POST['vehical_type']);
    $v_model = $conn->real_escape_string($_POST['vehical_model']);
    $v_num = $conn->real_escape_string($_POST['vehical_number']);
    
    $taking_date = $conn->real_escape_string($_POST['taking_date']);
    $handover_date = $conn->real_escape_string($_POST['handover_date']);
    $handover_type = $conn->real_escape_string($_POST['handover_type']);
    $total_cost = $conn->real_escape_string($_POST['total_cost']);
    $payment_method = $conn->real_escape_string($_POST['payment_method']);
    
    $slip_path = "";
    if ($payment_method === 'Bank Transfer' && isset($_FILES['payment_slip']) && $_FILES['payment_slip']['error'] == 0) {
        $upload_dir = 'uploads/slips/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $slip_path = $upload_dir . time() . '_' . basename($_FILES['payment_slip']['name']);
        move_uploaded_file($_FILES['payment_slip']['tmp_name'], $slip_path);
    }

    $sql = "INSERT INTO bookings (booking_number, customer_name, email, mobile, nic, vehical_type, vehical_model, vehical_number, booking_date, taking_date, handover_date, handover_type, total_cost, payment_method, payment_slip, status) 
            VALUES ('$booking_number', '$c_name', '$email', '$mobile', '$nic', '$v_type', '$v_model', '$v_num', CURDATE(), '$taking_date', '$handover_date', '$handover_type', '$total_cost', '$payment_method', '$slip_path', 'Pending')";

    if ($conn->query($sql)) {
        echo json_encode(['status' => 'success', 'booking_number' => $booking_number]);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    exit;
}

// 4. Process Review Submission
if ($action === 'submitReview') {
    $c_name = $conn->real_escape_string($_POST['customer_name']);
    // Cast the rating as an integer to prevent SQL injection
    $rating = (int)$_POST['rating']; 
    $review_text = $conn->real_escape_string($_POST['review_text']);

    $sql = "INSERT INTO feedbacks (customer_name, rating, review_text) 
            VALUES ('$c_name', '$rating', '$review_text')";

    if ($conn->query($sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save review.']);
    }
    exit;
}

?>