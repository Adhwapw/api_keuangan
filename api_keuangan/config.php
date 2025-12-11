<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// GANTI dengan kredensial InfinityFree Anda
$host = "sql213.infinityfree.com"; // Sesuaikan dengan host Anda
$db_name = "if0_38811659_manajemen_keuangan"; // Ganti xxxxx
$username = "if0_38811659"; // Ganti xxxxx
$password = "Adhwa122023"; // Password yang Anda buat

try {
    $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(array("success" => false, "message" => "Koneksi gagal: " . $e->getMessage()));
    exit();
}
?>
