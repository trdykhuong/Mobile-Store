<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $idctsp = isset($data['idctsp'])? intval($data['idctsp']) : 0;
    $text = isset($data['text'])? $data['text'] : 0;
    //khởi tạo giá trị cho output
  
    $sql_sp = mysqli_query($conn, "SELECT idSP from chitietsanpham WHERE idCTSP=" . intval($idctsp));
    $result_id = mysqli_fetch_assoc($sql_sp);

    $idsp = $result_id['idSP'];

    $sql = '';
    $message='';
    $status = 1;

    switch($text){
        case 'Khóa': $sql = "UPDATE sanpham SET TRANGTHAI=0 WHERE idSP=$idsp"; $message="Khóa sản phẩm $idsp thành công"; $status = 0; break;
        case 'Mở khóa': $sql = "UPDATE sanpham SET TRANGTHAI=1 WHERE idSP=$idsp"; $message="Mở khóa sản phẩm $idsp thành công"; break;
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