<?php
include 'connection.php';

// Menerima input dari Flutter
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 1; // Default user 1 jika belum ada login
$category_id = $_POST['category_id'];
$type = $_POST['type'];
$amount = $_POST['amount'];
$date = $_POST['date'];
$note = isset($_POST['note']) ? $_POST['note'] : '';

// Query Insert
$sql = "INSERT INTO transactions (user_id, category_id, type, amount, date, note) 
        VALUES ('$user_id', '$category_id', '$type', '$amount', '$date', '$note')";

if ($connect->query($sql) === TRUE) {
    echo json_encode(array("success" => true, "message" => "Transaksi berhasil disimpan"));
} else {
    echo json_encode(array("success" => false, "message" => "Error: " . $connect->error));
}

$connect->close();
?>