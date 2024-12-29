<?php

// Database connection details
$db_host = 'localhost';
$db_name = 'hotel1_db';
$db_user_name = 'root';
$db_user_pass = '';

try {
    // Create PDO connection
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user_name, $db_user_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Function to create a unique ID
function create_unique_id() {
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'), 0, 20);
}
?>
