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

// Azure MySQL Configuration - Using Environment Variables
$host = getenv('DB_HOST') ?: "manajemen-keuangan-db.mysql.database.azure.com";
$db_name = getenv('DB_NAME') ?: "manajemen_keuangan";
$username = getenv('DB_USER') ?: "adminuser";
$password = getenv('DB_PASSWORD') ?: "DEFAULT_PASSWORD"; // Will be overridden by Azure config

try {
    $conn = new PDO(
        "mysql:host=" . $host . ";dbname=" . $db_name . ";charset=utf8mb4",
        $username,
        $password,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => false,
            PDO::MYSQL_ATTR_SSL_CA => true,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
        )
    );
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(array(
        "success" => false, 
        "message" => "Database connection failed"
        // Error detail di-hide untuk production
    ));
    exit();
}
?>
