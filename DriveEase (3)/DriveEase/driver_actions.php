<?php
session_start();
header('Content-Type: application/json'); // Crucial for JS parsing
require 'db.php'; 

if (!isset($_SESSION['driver_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit;
}

$driver_id = $_SESSION['driver_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'addLocation') {
    $v_num = $conn->real_escape_string($_POST['vehical_number'] ?? '');
    $trip = $conn->real_escape_string($_POST['trip_details'] ?? '');
    $location = $conn->real_escape_string($_POST['current_location'] ?? '');
    $time_date = $conn->real_escape_string($_POST['time_and_date'] ?? '');
    $driver_name = $conn->real_escape_string($_POST['driver_name'] ?? '');

    $sql = "INSERT INTO tracking (driver_id, vehical_number, trip_details, current_location, time_and_date, driver_name) 
            VALUES ('$driver_id', '$v_num', '$trip', '$location', '$time_date', '$driver_name')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'fetchLocations') {
    $locations = [];
    $sql = "SELECT * FROM tracking WHERE driver_id='$driver_id' ORDER BY time_and_date DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $locations[] = $row;
        }
    }
    echo json_encode(['status' => 'success', 'data' => $locations]);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
?>