<?php
include 'config.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id) && !empty($data->user_id)) {
    $id = $data->id;
    $user_id = $data->user_id;
    
    // Check if wallet has transactions
    $query = "SELECT COUNT(*) as count FROM transaksi WHERE akun_id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($result['count'] > 0) {
        echo json_encode(array("success" => false, "message" => "Wallet tidak bisa dihapus karena masih ada transaksi"));
    } else {
        $query = "DELETE FROM saldo WHERE id = :id AND user_id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":user_id", $user_id);
        
        if($stmt->execute()) {
            echo json_encode(array("success" => true, "message" => "Wallet berhasil dihapus"));
        } else {
            echo json_encode(array("success" => false, "message" => "Wallet gagal dihapus"));
        }
    }
} else {
    echo json_encode(array("success" => false, "message" => "Data tidak lengkap"));
}
?>