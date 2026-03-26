<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $idNCC = isset($data['idNCC'])? $data['idNCC'] : '';
    $loinhuan = isset($data['loinhuan'])? intval($data['loinhuan']) : 1;
    $thanhtien = isset($data['thanhtien'])? floatval($data['thanhtien']) : 1;

    //Khỏi tạo phiếu nhập
    $pn_row = mysqli_query($conn, "SELECT idPN from phieunhap");
    $idPN = mysqli_num_rows($pn_row) + 1;

    //Làm tí ngày hiện tại
    $today = date('Y-m-d');

    $sql = "INSERT INTO phieunhap(idPN, idNCC, NGAYNHAP, THANHTIEN, LOINHUAN)
    VALUES($idPN, $idNCC, '$today', '$thanhtien' , $loinhuan)";

    $result = mysqli_query($conn, $sql);

    $response = [
        'id' => $idPN,
        'loinhuan' => $loinhuan,
        'thanhtien' => $thanhtien,
        'message' => "Thêm phiếu nhâp thành công", 
    ];

    $json = json_encode($response);
    echo $json;
}
?>