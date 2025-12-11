<?php
include 'config.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id) && !empty($data->user_id) && !empty($data->tipe) && !empty($data->jumlah) && !empty($data->kategori) && !empty($data->tanggal)) {
    $id = $data->id;
    $user_id = $data->user_id;
    $tipe = $data->tipe;
    $jumlah = $data->jumlah;
    $kategori = $data->kategori;
    $tanggal = $data->tanggal;
    $catatan = isset($data->catatan) ? $data->catatan : "";
    
    // Get old transaction data
    $query = "SELECT * FROM transaksi WHERE id = :id AND user_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    
    if($stmt->rowCount() > 0) {
        $old_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Update transaction
        $query = "UPDATE transaksi SET tipe = :tipe, jumlah = :jumlah, kategori = :kategori, tanggal = :tanggal, catatan = :catatan WHERE id = :id AND user_id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":tipe", $tipe);
        $stmt->bindParam(":jumlah", $jumlah);
        $stmt->bindParam(":kategori", $kategori);
        $stmt->bindParam(":tanggal", $tanggal);
        $stmt->bindParam(":catatan", $catatan);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":user_id", $user_id);
        
        if($stmt->execute()) {
            // Update saldo - revert old transaction
            $old_multiplier = ($old_data['tipe'] == 'pemasukan') ? -1 : 1;
            $query2 = "UPDATE saldo SET jumlah = jumlah + (:old_jumlah * :old_multiplier) WHERE user_id = :user_id AND nama_akun = 'Cash'";
            $stmt2 = $conn->prepare($query2);
            $stmt2->bindParam(":old_jumlah", $old_data['jumlah']);
            $stmt2->bindParam(":old_multiplier", $old_multiplier);
            $stmt2->bindParam(":user_id", $user_id);
            $stmt2->execute();
            
            // Apply new transaction
            $new_multiplier = ($tipe == 'pemasukan') ? 1 : -1;
            $query3 = "UPDATE saldo SET jumlah = jumlah + (:jumlah * :multiplier) WHERE user_id = :user_id AND nama_akun = 'Cash'";
            $stmt3 = $conn->prepare($query3);
            $stmt3->bindParam(":jumlah", $jumlah);
            $stmt3->bindParam(":multiplier", $new_multiplier);
            $stmt3->bindParam(":user_id", $user_id);
            $stmt3->execute();
            
            echo json_encode(array("success" => true, "message" => "Transaksi berhasil diupdate"));
        } else {
            echo json_encode(array("success" => false, "message" => "Transaksi gagal diupdate"));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Transaksi tidak ditemukan"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Data tidak lengkap"));
}
?>