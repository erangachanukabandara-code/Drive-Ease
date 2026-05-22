<?php
session_start();
require 'db.php';

// Security check
if (!isset($_SESSION['owner_id']) || $_SESSION['user_role'] !== 'owner') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit;
}

$owner_id = $_SESSION['owner_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ==========================================
// 1. VEHICLE MANAGEMENT
// ==========================================
if ($action === 'addVehicle' || $action === 'updateVehicle') {
    $v_id = $conn->real_escape_string($_POST['vehical_id'] ?? '');
    $v_num = $conn->real_escape_string($_POST['vehical_number']);
    $v_type = $conn->real_escape_string($_POST['vehical_type']);
    $v_model = $conn->real_escape_string($_POST['vehical_model']);
    $v_engine = $conn->real_escape_string($_POST['engine_capacity']);
    $v_price = $conn->real_escape_string($_POST['price_per_day']);
    $v_details = $conn->real_escape_string($_POST['additional_details']);
    
    $image_path = "";
    if (isset($_FILES['vehical_image']) && $_FILES['vehical_image']['error'] == 0) {
        $upload_dir = 'uploads/vehicles/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $image_path = $upload_dir . time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", basename($_FILES['vehical_image']['name']));
        move_uploaded_file($_FILES['vehical_image']['tmp_name'], $image_path);
    }

    if ($action === 'addVehicle') {
        if(empty($image_path)) { echo json_encode(['status'=>'error', 'message'=>'Vehicle image is required.']); exit; }
        $sql = "INSERT INTO owner_vehicals (owner_id, vehical_number, vehical_type, vehical_model, engine_capacity, price_per_day, additional_details, vehical_image) 
                VALUES ('$owner_id', '$v_num', '$v_type', '$v_model', '$v_engine', '$v_price', '$v_details', '$image_path')";
    } else {
        $imgSql = !empty($image_path) ? ", vehical_image='$image_path'" : "";
        $sql = "UPDATE owner_vehicals SET vehical_number='$v_num', vehical_type='$v_type', vehical_model='$v_model', engine_capacity='$v_engine', price_per_day='$v_price', additional_details='$v_details' $imgSql WHERE id='$v_id' AND owner_id='$owner_id'";
    }
    
    echo $conn->query($sql) ? json_encode(['status' => 'success']) : json_encode(['status' => 'error', 'message' => $conn->error]);
    exit;
}

if ($action === 'deleteVehicle') {
    $v_id = $conn->real_escape_string($_POST['vehical_id']);
    $sql = "DELETE FROM owner_vehicals WHERE id='$v_id' AND owner_id='$owner_id'";
    echo $conn->query($sql) ? json_encode(['status' => 'success']) : json_encode(['status' => 'error', 'message' => 'Failed to delete vehicle.']);
    exit;
}

// ==========================================
// 2. BOOKINGS MANAGEMENT
// ==========================================
if ($action === 'deleteBooking') {
    $b_id = $conn->real_escape_string($_POST['booking_id']);
    $sql = "DELETE FROM bookings WHERE id='$b_id'";
    echo $conn->query($sql) ? json_encode(['status' => 'success']) : json_encode(['status' => 'error', 'message' => 'Failed to delete booking.']);
    exit;
}

if ($action === 'updateBookingStatus') {
    $b_id = $conn->real_escape_string($_POST['booking_id']);
    $status = $conn->real_escape_string($_POST['status']);
    $sql = "UPDATE bookings SET status='$status' WHERE id='$b_id'";
    echo $conn->query($sql) ? json_encode(['status' => 'success']) : json_encode(['status' => 'error', 'message' => 'Failed to update status.']);
    exit;
}

// ==========================================
// 3. AVAILABILITY MANAGEMENT
// ==========================================
if ($action === 'addAvailability') {
    $v_num = $conn->real_escape_string($_POST['vehical_number']);
    $v_type = $conn->real_escape_string($_POST['vehical_type']);
    $v_model = $conn->real_escape_string($_POST['vehical_model']);
    $date = $conn->real_escape_string($_POST['available_date']);
    
    $sql = "INSERT INTO availability (vehical_number, vehical_type, vehical_model, available_date) VALUES ('$v_num', '$v_type', '$v_model', '$date')";
    echo $conn->query($sql) ? json_encode(['status' => 'success']) : json_encode(['status' => 'error', 'message' => $conn->error]);
    exit;
}

if ($action === 'deleteAvailability') {
    $id = $conn->real_escape_string($_POST['id']);
    $sql = "DELETE FROM availability WHERE id='$id'";
    echo $conn->query($sql) ? json_encode(['status' => 'success']) : json_encode(['status' => 'error', 'message' => 'Failed to delete availability.']);
    exit;
}

// ==========================================
// 4. DEALS MANAGEMENT
// ==========================================
if ($action === 'addDeal') {
    $mode = $conn->real_escape_string($_POST['deal_mode']);
    $title = $conn->real_escape_string($_POST['deal_title'] ?? '');
    $highlight = $conn->real_escape_string($_POST['highlighted_text'] ?? '');
    $details = $conn->real_escape_string($_POST['details'] ?? '');
    
    $image_path = "";
    if (isset($_FILES['deal_image']) && $_FILES['deal_image']['error'] == 0) {
        $upload_dir = 'uploads/deals/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $image_path = $upload_dir . time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", basename($_FILES['deal_image']['name']));
        move_uploaded_file($_FILES['deal_image']['tmp_name'], $image_path);
    }

    $sql = "INSERT INTO deals (deal_mode, deal_title, highlighted_text, details, deal_image) VALUES ('$mode', '$title', '$highlight', '$details', '$image_path')";
    echo $conn->query($sql) ? json_encode(['status' => 'success']) : json_encode(['status' => 'error', 'message' => $conn->error]);
    exit;
}

if ($action === 'deleteDeal') {
    $d_id = $conn->real_escape_string($_POST['deal_id']);
    $sql = "DELETE FROM deals WHERE id='$d_id'";
    echo $conn->query($sql) ? json_encode(['status' => 'success']) : json_encode(['status' => 'error', 'message' => 'Failed to delete deal.']);
    exit;
}

// ==========================================
// 5. PROFILE MANAGEMENT (Updated)
// ==========================================
if ($action === 'updateProfileDetails') {
    $name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $address = $conn->real_escape_string($_POST['address']);
    
    $imgSql = "";
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $upload_dir = 'uploads/owners/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $image_path = $upload_dir . time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", basename($_FILES['profile_image']['name']));
        
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $image_path)) {
            $imgSql = ", profile_image='$image_path'";
        }
    }
    
    $sql = "UPDATE owner_accounts SET full_name='$name', email='$email', mobile='$mobile', address='$address' $imgSql WHERE id='$owner_id'";
    if ($conn->query($sql)) {
        $_SESSION['owner_name'] = $name; 
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update database.']);
    }
    exit;
}

if ($action === 'removeProfileImage') {
    $sql = "UPDATE owner_accounts SET profile_image='' WHERE id='$owner_id'";
    if ($conn->query($sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to remove image from database.']);
    }
    exit;
}

// ==========================================
// 6. DATA FETCHING (GET REQUESTS)
// ==========================================
if ($action === 'fetchAllData') {
    $data = ['vehicles' => [], 'bookings' => [], 'availability' => [], 'deals' => [], 'profile' => []];
    
    $res = $conn->query("SELECT * FROM owner_vehicals WHERE owner_id='$owner_id' ORDER BY id DESC");
    while($row = $res->fetch_assoc()) $data['vehicles'][] = $row;
    
    // Using a simple JOIN to get bookings related to this owner's vehicles
    $res = $conn->query("SELECT b.* FROM bookings b JOIN owner_vehicals v ON b.vehical_number = v.vehical_number WHERE v.owner_id='$owner_id' ORDER BY b.id DESC");
    if($res) { while($row = $res->fetch_assoc()) $data['bookings'][] = $row; }
    
    $res = $conn->query("SELECT a.* FROM availability a JOIN owner_vehicals v ON a.vehical_number = v.vehical_number WHERE v.owner_id='$owner_id' ORDER BY a.available_date ASC");
    if($res) { while($row = $res->fetch_assoc()) $data['availability'][] = $row; }
    
    $res = $conn->query("SELECT * FROM deals ORDER BY id DESC");
    if($res) { while($row = $res->fetch_assoc()) $data['deals'][] = $row; }

    $res = $conn->query("SELECT full_name, email, mobile, address, profile_image FROM owner_accounts WHERE id='$owner_id'");
    if($row = $res->fetch_assoc()) $data['profile'] = $row;

    echo json_encode($data);
    exit;
}

// ==========================================
// Track Vehicle
// ==========================================
if ($action === 'trackVehicle') {
    header('Content-Type: application/json'); // Force strict JSON
    
    $v_num = $conn->real_escape_string($_POST['vehical_number']);
    
    // Updated to use 'time_and_date' instead of 'last_updated'
    $sql = "SELECT * FROM tracking WHERE vehical_number='$v_num' ORDER BY time_and_date DESC LIMIT 1";
    $res = $conn->query($sql);
    
    // Added a safety check to ensure the query actually succeeded before fetching
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        echo json_encode(['status' => 'success', 'data' => $row]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No tracking data found for this vehicle number.']);
    }
    exit;
}
?>