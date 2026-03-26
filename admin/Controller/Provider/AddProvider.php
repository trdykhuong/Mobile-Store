<?php
$conn = mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $ten = isset($data['ten'])? $data['ten'] : '';
    $sdt = isset($data['sdt'])? $data['sdt'] : '';
    $diachi = isset($data['diachi'])? $data['diachi'] : '';

    //Khai báo output
    $result = '';
    $status = 0;
    //Tính id của hiện tại của nhà cung cấp
    $id_temp = mysqli_query($conn, "SELECT idNCC FROM nhacungcap");
    $num_rows_id = mysqli_num_rows($id_temp) + 1;

    // Kiểm tra xem nhà cung cấp đã tồn tại trong cơ sở dữ liệu chưa
    $sql_check_provider = mysqli_query($conn, "SELECT * FROM nhacungcap WHERE TENNCC = '$ten'");
    $num_rows = mysqli_num_rows($sql_check_provider);

    if($num_rows > 0){
        $result = "Nhà cung cấp đã tồn tại";
        $status = 1;
    }else{
        $sql = "INSERT INTO nhacungcap (idNCC, TENNCC, SDT, DIACHI) VALUES ('$num_rows_id', '$ten', '$sdt', '$diachi')";
        ///Chạy sql
        mysqli_query($conn, $sql);
        $result = "Thim mới Nhà cung cấp thành công"; 
        $status = 0;
    }

    $response = [
        'message' => $result,
        'status' => $status
    ];

    $json = json_encode($response);
    echo $json;
}
?>