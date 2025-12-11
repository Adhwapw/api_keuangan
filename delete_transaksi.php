<?php
include 'config.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id) && !empty($data->user_id)) {
    $id = $data->id;
    $user_id = $data->user_id;
    
    // Get transaction data
    $query = "SELECT * FROM transaksi WHERE id = :id AND user_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    
    if($stmt->rowCount() > 0) {
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Delete transaction
        $query = "DELETE FROM transaksi WHERE id = :id AND user_id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":user_id", $user_id);
        
        if($stmt->execute()) {
            // Update saldo - revert transaction
            $multiplier = ($data['tipe'] == 'pemasukan') ? -1 : 1;
            $query2 = "UPDATE saldo SET jumlah = jumlah + (:jumlah * :multiplier) WHERE user_id = :user_id AND nama_akun = 'Cash'";
            $stmt2 = $conn->prepare($query2);
            $stmt2->bindParam(":jumlah", $data['jumlah']);
            $stmt2->bindParam(":multiplier", $multiplier);
            $stmt2->bindParam(":user_id", $user_id);
            $stmt2->execute();
            
            echo json_encode(array("success" => true, "message" => "Transaksi berhasil dihapus"));
        } else {
            echo json_encode(array("success" => false, "message" => "Transaksi gagal dihapus"));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Transaksi tidak ditemukan"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Data tidak lengkap"));
}
?>