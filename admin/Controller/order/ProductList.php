<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $sql = "SELECT idSP, TENSP, IMG, GIA, DISCOUNT, SOLUONG from sanpham WHERE TRANGTHAI=1 AND SOLUONG > 0";

    $result = mysqli_query($conn, $sql);
    $list_sp = array();

    while($type = mysqli_fetch_array($result)){
        $id = $type['idSP'];
        $name = $type['TENSP'];
        $img = $type['IMG'];
        $gia = $type['GIA'];
        $discount = $type['DISCOUNT'];
        $soluong = $type['SOLUONG'];

        $arr = array(
            'id' => $id,
            'name' => $name,
            'img' => $img,
            'gia' => $gia,
            'discount' => $discount,
            'soluong' => $soluong
        );

        array_push($list_sp, $arr);
    }
    //Trả về list sp
    $json = json_encode($list_sp);
    echo $json;
}
?>