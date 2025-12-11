<?php
include 'config.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->email) && !empty($data->password)) {
    $email = $data->email;
    $password = $data->password;
    
    $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    
    if($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(password_verify($password, $row['password'])) {
            echo json_encode(array(
                "success" => true, 
                "message" => "Login berhasil",
                "user" => array(
                    "id" => $row['id'],
                    "nama" => $row['nama'],
                    "email" => $row['email']
                )
            ));
        } else {
            echo json_encode(array("success" => false, "message" => "Password salah"));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Email tidak ditemukan"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Data tidak lengkap"));
}
?>