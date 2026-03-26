<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $idNCC = isset($data['idNCC'])? intval($data['idNCC']) : 0;
    $text = isset($data['text'])? $data['text'] : 0;
    //khởi tạo giá trị cho output
    $sql = '';
    $message='';
    $status = 1;

    switch($text){
        case 'Khóa': $sql = "UPDATE nhacungcap SET TRANGTHAI=0 WHERE idNCC=$idNCC"; $message="Khóa Nhà cung cấp NCC$idNCC thành công"; $status = 0; break;
        case 'Mở khóa': $sql = "UPDATE nhacungcap SET TRANGTHAI=1 WHERE idNCC=$idNCC"; $message="Mở khóa Nhà cung cấp NCC$idNCC thành công"; break;
        default: break;
    }
    
    mysqli_query($conn, $sql);

    $response = [
        'message' => $message,
        'trangthaimoi' => $status
    ];

    $json = json_encode($response);
    echo $json;
}
?>