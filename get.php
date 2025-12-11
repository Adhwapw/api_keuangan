<?php
include 'connection.php';

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 1;

// Mengambil data transaksi digabung dengan data kategori
$sql = "SELECT t.id, t.type, t.amount, t.date, t.note, c.name as category_name, c.icon_name 
        FROM transactions t
        JOIN categories c ON t.category_id = c.id
        WHERE t.user_id = '$user_id'
        ORDER BY t.date DESC, t.id DESC";

$result = $connect->query($sql);

$data = array();
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
$connect->close();
?>