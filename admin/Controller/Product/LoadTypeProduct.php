<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $idSP = isset($_GET['idSP'])? $_GET['idSP'] : '';
    $sql = '';
    $list = array();

    $sql = "SELECT * from chitietsanpham WHERE idSP = $idSP";
    $result = mysqli_query($conn, $sql);

        while($sanpham = mysqli_fetch_array($result)){
            $img = $sanpham['IMG'];
            $giathem = $sanpham['DIEUCHINHGIA'];
            $mausac = $sanpham['MAUSAC'];
            $dungluong = $sanpham['DUNGLUONG'];
    
            $arr = [
                'img' => $img,
                'giathem' => $giathem,
                'mausac' => $mausac,
                'dungluong' => $dungluong
            ];
    
            array_push($list, $arr);
        }

    $json = json_encode($list);
    echo $json;
}
?>