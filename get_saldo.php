<?php
include 'config.php';

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die(json_encode(array("success" => false, "message" => "User ID tidak ditemukan")));

$query = "SELECT * FROM saldo WHERE user_id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(":user_id", $user_id);
$stmt->execute();

$saldo = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(array("success" => true, "data" => $saldo));
?>