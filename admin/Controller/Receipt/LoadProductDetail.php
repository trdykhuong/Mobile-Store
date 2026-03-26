<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');   
    $data = json_decode($json, true);

    $idSP = isset($data['idSP'])? $data['idSP'] : '';
    $mausac = isset($data['mausac'])? $data['mausac'] : '';
    $dungluong = isset($data['dungluong'])? $data['dungluong'] : '';

    $sql = 'SELECT s.idSP, s.TENSP, s.IMG, s.GIANHAP, ct.MAUSAC, ct.DUNGLUONG, ct.DIEUCHINHGIA from sanpham s, chitietsanpham ct WHERE s.idSP = ct.idSP AND s.idSP=' . intval($idSP) . ' AND ct.MAUSAC = "' . $mausac . '" AND ct.DUNGLUONG = "' . $dungluong . '"';

    $result = mysqli_query($conn, $sql);
    while($sp = mysqli_fetch_array($result)){
        $idsp = $sp['idSP'];
        $tensp = $sp['TENSP'];
        $img = $sp['IMG'];
        $gianhap = $sp['GIANHAP'];
        $mausac =  $sp['MAUSAC'];
        $dungluong = $sp['DUNGLUONG'];
        $giathem = $sp['DIEUCHINHGIA'];

        $response = ['id' => $idsp,
            'name' => $tensp,
            'img' => $img,
            'gianhap' => $gianhap,
            'mausac' => $mausac,
            'dungluong' => $dungluong,
            'giathem' => $giathem
        ];
    }
    
    $json = json_encode( $response);
    echo $json;
}
?>