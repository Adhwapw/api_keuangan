<?php
include 'config.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_id) && !empty($data->tipe) && !empty($data->jumlah) && !empty($data->kategori) && !empty($data->tanggal)) {
    $user_id = $data->user_id;
    $akun_id = isset($data->akun_id) ? $data->akun_id : null;  // NEW
    $tipe = $data->tipe;
    $jumlah = $data->jumlah;
    $kategori = $data->kategori;
    $tanggal = $data->tanggal;
    $catatan = isset($data->catatan) ? $data->catatan : "";
    
    // Insert transaction with akun_id
    $query = "INSERT INTO transaksi (user_id, akun_id, tipe, jumlah, kategori, tanggal, catatan) 
              VALUES (:user_id, :akun_id, :tipe, :jumlah, :kategori, :tanggal, :catatan)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->bindParam(":akun_id", $akun_id);  // NEW
    $stmt->bindParam(":tipe", $tipe);
    $stmt->bindParam(":jumlah", $jumlah);
    $stmt->bindParam(":kategori", $kategori);
    $stmt->bindParam(":tanggal", $tanggal);
    $stmt->bindParam(":catatan", $catatan);
    
    if($stmt->execute()) {
        // Update saldo of specific wallet
        if($akun_id != null) {
            $multiplier = ($tipe == 'pemasukan') ? 1 : -1;
            $query2 = "UPDATE saldo SET jumlah = jumlah + (:jumlah * :multiplier) WHERE id = :akun_id";
            $stmt2 = $conn->prepare($query2);
            $stmt2->bindParam(":jumlah", $jumlah);
            $stmt2->bindParam(":multiplier", $multiplier);
            $stmt2->bindParam(":akun_id", $akun_id);
            $stmt2->execute();
        }
        
        echo json_encode(array("success" => true, "message" => "Transaksi berhasil ditambahkan"));
    } else {
        echo json_encode(array("success" => false, "message" => "Transaksi gagal"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Data tidak lengkap"));
}
?>