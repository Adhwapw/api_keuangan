<?php
include 'connection.php';

$sql = "SELECT * FROM categories ORDER BY id ASC";
$result = $connect->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
$connect->close();
?>