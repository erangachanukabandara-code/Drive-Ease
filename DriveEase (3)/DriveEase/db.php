<?php
$host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'drive_ease_db';

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed!']));
}
?>