<?php
include 'config.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->nama) && !empty($data->email) && !empty($data->password)) {
    $nama = $data->nama;
    $email = $data->email;
    $password = password_hash($data->password, PASSWORD_DEFAULT);
    
    $query = "INSERT INTO users (nama, email, password) VALUES (:nama, :email, :password)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":nama", $nama);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $password);
    
    if($stmt->execute()) {
        $user_id = $conn->lastInsertId();
        
        // Buat saldo default
        $query2 = "INSERT INTO saldo (user_id, nama_akun, jumlah) VALUES (:user_id, 'Cash', 0), (:user_id2, 'Bank', 0)";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bindParam(":user_id", $user_id);
        $stmt2->bindParam(":user_id2", $user_id);
        $stmt2->execute();
        
        echo json_encode(array("success" => true, "message" => "Registrasi berhasil", "user_id" => $user_id));
    } else {
        echo json_encode(array("success" => false, "message" => "Registrasi gagal"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Data tidak lengkap"));
}
?>