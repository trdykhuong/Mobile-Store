<?php
$conn = mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $text = isset($data['text'])? $data['text'] : '';
    $order = isset($data['order'])? intval($data['order']) : '';

    //Móc ra sản phẩm
    $sql = 'SELECT * from nhacungcap ';
    //Khởi tạo mấy cái sql
    $sql_search = !$text == ''? " WHERE TENNCC LIKE N'%$text%' OR DIACHI LIKE N'%$text%' " : '';

    $sql_order = $order == ''? "" : ( $sql_search == ''?  " WHERE TRANGTHAI=$order" :  " AND TRANGTHAI=$order");

    //Nối mấy sql lại
    $sql .= ( $sql_search . $sql_order);

    ///Chạy sql
    $result = mysqli_query($conn, $sql);
    $providerlist = array();

    //Chạy truy vấn lưu từng sp vào mảng
    while($ncc = mysqli_fetch_assoc($result)){
        $idncc = $ncc['idNCC'];
        $tenncc = $ncc['TENNCC'];
        $sdt = $ncc['SDT'];
        $diachi = $ncc['DIACHI'];
        $trangthai = $ncc['TRANGTHAI'];

        $arr = ['id' => $idncc,
            'ten' => $tenncc,
            'sdt' => $sdt,
            'diachi' => $diachi,
            'trangthai' => $trangthai
        ];

        array_push($providerlist, $arr);
    }

    $json = json_encode($providerlist);
    echo $json;
}
?>