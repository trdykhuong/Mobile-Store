<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $idNCC = isset($_GET['idNCC'])? $_GET['idNCC'] : '';

    //Móc ra nhà cung cấp
    $sql = 'SELECT * from nhacungcap WHERE idNCC=' . intval($idNCC);

    $result = mysqli_query($conn, $sql);
    //Chạy truy vấn ncc
    while($ncc = mysqli_fetch_assoc($result)){
        $idncc = $ncc['idNCC'];
        $tenncc = $ncc['TENNCC'];
        $sdt = $ncc['SDT'];
        $diachi = $ncc['DIACHI'];
        $trangthai = $ncc['TRANGTHAI'];
    }

    $response = [
        'id' => $idncc,
        'ten' => $tenncc,
        'sdt' => $sdt,
        'diachi' => $diachi,
        'trangthai' => $trangthai
    ];
    
    $json = json_encode($response);
    echo $json;
}
?>