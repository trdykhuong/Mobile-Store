<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');   
    $data = json_decode($json, true);

    $idSP = isset($data['idSP'])? $data['idSP'] : '';

    $sql = 'SELECT s.idSP, s.TENSP, s.IMG, s.GIANHAP, ct.MAUSAC, ct.DUNGLUONG, ct.DIEUCHINHGIA, s.idDM 
    from sanpham s JOIN chitietsanpham ct ON s.idSP=ct.idSP WHERE (ct.MAUSAC <> "NULL" AND ct.DUNGLUONG <> "NULL") AND s.idSP=' . intval($idSP);

    $result = mysqli_query($conn, $sql);
    $list = array();
    while($sp = mysqli_fetch_array($result)){
        $idsp = $sp['idSP'];
        $tensp = $sp['TENSP'];
        $img = $sp['IMG'];
        $gianhap = $sp['GIANHAP'];
        $mausac =  $sp['MAUSAC'];
        $dungluong = $sp['DUNGLUONG'];
        $giathem = $sp['DIEUCHINHGIA'];
        $danhmuc = $sp['idDM'];

        $arr = ['id' => $idsp,
            'name' => $tensp,
            'img' => $img,
            'gianhap' => $gianhap,
            'mausac' => $mausac,
            'dungluong' => $dungluong,
            'giathem' => $giathem,
            'danhmuc' => $danhmuc
        ];
        array_push($list, $arr);
    }
    
    $json = json_encode($list);
    echo $json;
}
?>