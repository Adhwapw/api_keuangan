<?php
include 'config.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id) && !empty($data->user_id) && !empty($data->nama_akun)) {
    $id = $data->id;
    $user_id = $data->user_id;
    $nama_akun = $data->nama_akun;
    $icon = isset($data->icon) ? $data->icon : 'wallet';
    $color = isset($data->color) ? $data->color : 'blue';
    
    $query = "UPDATE saldo SET nama_akun = :nama_akun, icon = :icon, color = :color WHERE id = :id AND user_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":nama_akun", $nama_akun);
    $stmt->bindParam(":icon", $icon);
    $stmt->bindParam(":color", $color);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":user_id", $user_id);
    
    if($stmt->execute()) {
        echo json_encode(array("success" => true, "message" => "Wallet berhasil diupdate"));
    } else {
        echo json_encode(array("success" => false, "message" => "Wallet gagal diupdate"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Data tidak lengkap"));
}
?>