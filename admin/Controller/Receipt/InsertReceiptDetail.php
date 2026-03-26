<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $idPN = isset($data['idPN'])? $data['idPN'] : '';
    $idSP = isset($data['idSP'])? $data['idSP'] : '';
    $soluong = isset($data['soluong'])? $data['soluong'] : '';
    $gianhap = isset($data['gianhap'])? $data['gianhap'] : '';
    $giathem = isset($data['giathem'])? $data['giathem'] : '';
    $mausac = isset($data['mausac'])? $data['mausac'] : 'KHÔNG CÓ';
    $dungluong = isset($data['dungluong'])? $data['dungluong'] : 'KHÔNG CÓ';
    $loinhuan = isset($data['loinhuan'])? $data['loinhuan'] : '';

    $giaban = intval($gianhap) * (1 + intval($loinhuan)/100);

    $sql_select = "SELECT * FROM chitietsanpham WHERE idSP = $idSP AND MAUSAC = '$mausac' AND DUNGLUONG = '$dungluong'";
    $result = mysqli_query($conn, $sql_select);

    $idCTSP = '';
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $idCTSP = $row['idCTSP'];  // Lấy ra idCTSP từ kết quả
    } 

    //Insert vào chitietphieunnhap
    $sql = "INSERT INTO chitietphieunhap (idPN, idCTSP, SOLUONG, DIEUCHINHGIA, GIANHAP)
    VALUES($idPN, $idCTSP, $soluong, $giathem, $gianhap)";

    mysqli_query($conn, $sql);

    //Update số lượng và giá vào bảng sản phửm
    mysqli_query($conn, "UPDATE chitietsanpham SET TONKHO = TONKHO + " . intval($soluong) . ", DIEUCHINHGIA = " . intval($giathem) . " WHERE idCTSP=" . intval($idCTSP));

    mysqli_query($conn, "UPDATE sanpham SET GIANHAP = " . intval($gianhap) . ", GIA = " . intval($giaban) . " WHERE idSP=" . intval($idSP));
    
    $response = [
        'message' => 'Đã thêm sản phẩm ' . intval($idCTSP) . ' vào ' . intval($idPN)
    ];

    $json = json_encode($response);
    echo $json;
}
?>