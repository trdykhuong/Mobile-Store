<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $idSP = isset($_GET['idSP'])? $_GET['idSP'] : '';

    $list = array();

    //Móc ra sản phẩm 
    $sql = 'SELECT s.idSP, ct.idCTSP, s.TENSP, s.IMG, s.GIANHAP, s.GIA, s.DISCOUNT, s.MOTA, s.HANG, s.idDM, s.TRANGTHAI, ct.MAUSAC, ct.DUNGLUONG, ct.DIEUCHINHGIA from sanpham s JOIN chitietsanpham ct ON s.idSP = ct.idSP WHERE s.idSP=' . intval($idSP);
    $result = mysqli_query($conn, $sql);

    //Chạy truy vấn sản phẩm
    while($sp = mysqli_fetch_assoc($result)){
        $idCTSP = $sp['idCTSP'];
        $tensp = $sp['TENSP'];
        $img = $sp['IMG'];
        $gianhap = $sp['GIANHAP'];
        $giaban = $sp['GIA'];
        $giathem = $sp['DIEUCHINHGIA'];
        $discount = $sp['DISCOUNT'];
        $hang = $sp['HANG'];
        $dm = $sp['idDM'];
        $mota = $sp['MOTA'];
        $trangthai = $sp['TRANGTHAI'];
        $mausac = $sp['MAUSAC'];
        $dungluong = $sp['DUNGLUONG'];

        $arr = [
            'idCTSP' => $idCTSP,
            'tensp' => $tensp,
            'img' => $img,
            'gianhap' => $gianhap,
            'giaban' => $giaban,
            'giathem' => $giathem,
            'discount' => $discount,
            'hang' => $hang,
            'dm' => $dm,
            'mota' => $mota,
            'trangthai' => $trangthai,
            'mausac' => $mausac,
            'dungluong' => $dungluong
        ];

        array_push($list, $arr);

    }

    $json = json_encode($list);
    echo $json;
}
?>