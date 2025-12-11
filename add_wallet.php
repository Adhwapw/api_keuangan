<?php
include 'config.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_id) && !empty($data->nama_akun)) {
    $user_id = $data->user_id;
    $nama_akun = $data->nama_akun;
    $icon = isset($data->icon) ? $data->icon : 'wallet';
    $color = isset($data->color) ? $data->color : 'blue';
    
    // Check if wallet name already exists for this user
    $query = "SELECT * FROM saldo WHERE user_id = :user_id AND nama_akun = :nama_akun";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->bindParam(":nama_akun", $nama_akun);
    $stmt->execute();
    
    if($stmt->rowCount() > 0) {
        echo json_encode(array("success" => false, "message" => "Wallet dengan nama ini sudah ada"));
    } else {
        $query = "INSERT INTO saldo (user_id, nama_akun, icon, color, jumlah) VALUES (:user_id, :nama_akun, :icon, :color, 0)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":nama_akun", $nama_akun);
        $stmt->bindParam(":icon", $icon);
        $stmt->bindParam(":color", $color);
        
        if($stmt->execute()) {
            echo json_encode(array("success" => true, "message" => "Wallet berhasil ditambahkan"));
        } else {
            echo json_encode(array("success" => false, "message" => "Wallet gagal ditambahkan"));
        }
    }
} else {
    echo json_encode(array("success" => false, "message" => "Data tidak lengkap"));
}
?>