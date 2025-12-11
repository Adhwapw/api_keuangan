<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Azure MySQL Configuration
$host = "manajemen-keuangan-db.mysql.database.azure.com";
$db_name = "manajemen_keuangan";
$username = "adminuser";
$password = "Alamanda@123"; 

// Create MySQLi connection
$connect = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($connect->connect_error) {
    http_response_code(500);
    echo json_encode(array(
        "success" => false,
        "message" => "Database connection failed",
        "error" => $connect->connect_error
    ));
    exit();
}

// Set charset to utf8mb4
$connect->set_charset("utf8mb4");
?>
