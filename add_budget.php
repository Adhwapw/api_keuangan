<?php
include 'config.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_id) && !empty($data->kategori) && !empty($data->jumlah)) {
    $user_id = $data->user_id;
    $kategori = $data->kategori;
    $jumlah = $data->jumlah;
    
    // Check if budget exists
    $query = "SELECT * FROM anggaran WHERE user_id = :user_id AND kategori = :kategori";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->bindParam(":kategori", $kategori);
    $stmt->execute();
    
    if($stmt->rowCount() > 0) {
        // Update existing
        $query = "UPDATE anggaran SET jumlah = :jumlah WHERE user_id = :user_id AND kategori = :kategori";
    } else {
        // Insert new
        $query = "INSERT INTO anggaran (user_id, kategori, jumlah, periode_mulai, periode_selesai) 
                  VALUES (:user_id, :kategori, :jumlah, CURDATE(), LAST_DAY(CURDATE()))";
    }
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->bindParam(":kategori", $kategori);
    $stmt->bindParam(":jumlah", $jumlah);
    
    if($stmt->execute()) {
        echo json_encode(array("success" => true, "message" => "Budget berhasil disimpan"));
    } else {
        echo json_encode(array("success" => false, "message" => "Budget gagal disimpan"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Data tidak lengkap"));
}
?>