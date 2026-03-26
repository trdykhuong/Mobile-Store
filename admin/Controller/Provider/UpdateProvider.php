<?php
$conn = mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $id = isset($data['idNCC'])? intval($data['idNCC']) : 0;
    $ten = isset($data['ten'])? $data['ten'] : '';
    $sdt = isset($data['sdt'])? $data['sdt'] : '';
    $diachi = isset($data['diachi'])? $data['diachi'] : '';

    $sql = "UPDATE nhacungcap SET TENNCC='$ten', SDT='$sdt', DIACHI='$diachi' WHERE idNCC=$id";

    ///Chạy sql
    $result = mysqli_query($conn, $sql)? "Cập nhật Nhà cung cấp NCC$id thành công" : "Sai sai cái j đó r á fen";  

    $response = [
        'message' => $result
    ];

    $json = json_encode($response);
    echo $json;
}
?>