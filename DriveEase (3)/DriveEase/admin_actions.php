<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

// Security check: Only allow logged-in admins
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// --- 1. FETCH ALL DASHBOARD & TABLE DATA ---
if ($action === 'fetchAllData') {
    $data = [
        'stats' => ['costs' => 0, 'vehicles' => 0, 'bookings' => 0, 'users' => 0, 'owners' => 0],
        'owners' => [], 'drivers' => [], 'inquiries' => [], 'feedbacks' => [], 
        'bookings' => [], 'vehicles' => [], 'deals' => [], 'users' => [], 'trackings' => []
    ];

    // Stats
    $costRes = $conn->query("SELECT SUM(total_cost) AS total FROM bookings WHERE status != 'Cancel'");
    if ($costRes && $row = $costRes->fetch_assoc()) $data['stats']['costs'] = $row['total'] ?? 0;

    $vehRes = $conn->query("SELECT COUNT(*) AS total FROM availability");
    if ($vehRes && $row = $vehRes->fetch_assoc()) $data['stats']['vehicles'] = $row['total'];

    $bookRes = $conn->query("SELECT COUNT(*) AS total FROM bookings");
    if ($bookRes && $row = $bookRes->fetch_assoc()) $data['stats']['bookings'] = $row['total'];

    $userRes = $conn->query("SELECT COUNT(*) AS total FROM users");
    if ($userRes && $row = $userRes->fetch_assoc()) $data['stats']['users'] = $row['total'];

    $ownerRes = $conn->query("SELECT COUNT(*) AS total FROM owner_accounts");
    if ($ownerRes && $row = $ownerRes->fetch_assoc()) $data['stats']['owners'] = $row['total'];

    // Table Data Fetching
    $res = $conn->query("SELECT * FROM owner_accounts ORDER BY id DESC");
    if($res) while($row = $res->fetch_assoc()) $data['owners'][] = $row;

    $res = $conn->query("SELECT * FROM drivers ORDER BY id DESC");
    if($res) while($row = $res->fetch_assoc()) $data['drivers'][] = $row;

    $res = $conn->query("SELECT * FROM inquiries ORDER BY id DESC");
    if($res) while($row = $res->fetch_assoc()) $data['inquiries'][] = $row;

    $res = $conn->query("SELECT * FROM feedbacks ORDER BY id DESC");
    if($res) while($row = $res->fetch_assoc()) $data['feedbacks'][] = $row;

    $res = $conn->query("SELECT * FROM bookings ORDER BY id DESC");
    if($res) while($row = $res->fetch_assoc()) $data['bookings'][] = $row;

    $res = $conn->query("SELECT * FROM availability ORDER BY available_date ASC");
    if($res) while($row = $res->fetch_assoc()) $data['vehicles'][] = $row;

    $res = $conn->query("SELECT * FROM deals ORDER BY id DESC");
    if($res) while($row = $res->fetch_assoc()) $data['deals'][] = $row;

    $res = $conn->query("SELECT * FROM users ORDER BY id DESC");
    if($res) while($row = $res->fetch_assoc()) $data['users'][] = $row;

    $res = $conn->query("SELECT * FROM tracking ORDER BY time_and_date DESC");
    if($res) while($row = $res->fetch_assoc()) $data['trackings'][] = $row;

    echo json_encode(['status' => 'success', 'data' => $data]);
    exit;
}

// --- 2. DELETE ACTIONS ---
function executeDelete($conn, $table, $column, $value) {
    $val = $conn->real_escape_string($value);
    $sql = "DELETE FROM $table WHERE $column='$val'";
    if ($conn->query($sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
    }
    exit;
}

if ($action === 'deleteOwner') executeDelete($conn, 'owner_accounts', 'id', $_POST['id']);
if ($action === 'deleteDriver') executeDelete($conn, 'drivers', 'id', $_POST['id']);
if ($action === 'deleteInquiry') executeDelete($conn, 'inquiries', 'id', $_POST['id']);
if ($action === 'deleteFeedback') executeDelete($conn, 'feedbacks', 'id', $_POST['id']);
if ($action === 'deleteBooking') executeDelete($conn, 'bookings', 'booking_number', $_POST['booking_number']);
if ($action === 'deleteDeal') executeDelete($conn, 'deals', 'id', $_POST['id']);
if ($action === 'deleteUser') executeDelete($conn, 'users', 'id', $_POST['id']);

echo json_encode(['status' => 'error', 'message' => 'Invalid Action']);
?>