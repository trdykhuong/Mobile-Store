<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $id = isset($data['idNV']) ? $data['idNV'] : null;
    $month = isset($data['month']) ? $data['month'] : null;
    $year = isset($data['year']) ? $data['year'] : null;
    $phucap = isset($data['phucap']) ? $data['phucap'] : 0;
    $khautru = isset($data['khautru']) ? $data['khautru'] : 0;
    $tongtien = isset($data['tongtien']) ? $data['tongtien'] : 0;
    $ghichu = isset($data['ghichu']) ? $data['ghichu'] : null;

    if($month < 10) $month = '0'. $month; //Tháng lưu đủ 2 số

    $sql = "INSERT INTO bangluong (idNV, THANG, NAM, PHUCAP, KHAUTRU, TONGTIEN, GHICHU) VALUES
    ($id, '$month', '$year', $phucap, $khautru, $tongtien, '$ghichu')";

    //Chạy sql r ấy kết quả thôi, cơ bản
    $message = '';  
    if(!mysqli_query($conn, $sql)){
        $message = 'Cập nhặt phiếu lương thành kong';
    }else{
        $message = 'Lỗi cái j đó r';
    }
    //Này cx cơ bản
    $response = [
        'message' => $message
    ];

    $json = json_encode($response);
    echo $json;
}

mysqli_close($conn);

?>