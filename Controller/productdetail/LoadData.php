<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $type = isset($_GET['type'])? $_GET['type'] : '';
    $sql = '';
    switch($type){
        case 'brand6': $sql="SELECT h.idHANG AS idTYPE, h.TENHANG AS TYPENAME, COUNT(sp.idSP) AS SOLUONG
                            FROM hang h
                            JOIN sanpham sp ON h.idHANG = sp.HANG
                            JOIN chitietsanpham ctsp ON sp.idSP = ctsp.idSP
                            WHERE sp.TRANGTHAI = 1 AND h.TRANGTHAI=1
                            GROUP BY h.idHANG, h.TENHANG
                            ORDER BY SOLUONG DESC
                            LIMIT 6"; break;
        case 'brand': $sql="SELECT idHANG AS idTYPE, TENHANG AS TYPENAME from hang WHERE TRANGTHAI=1"; break;
        case 'cagetory': $sql="SELECT idDM AS idTYPE, LOAISP AS TYPENAME from danhmuc WHERE TRANGTHAI=1"; break;
        default: break;
    }

    $type_list = array();

    $result = mysqli_query($conn, $sql);

    while($type_row = mysqli_fetch_assoc($result)){

        $idtype= $type_row['idTYPE'];
        $typename = $type_row['TYPENAME'];

        $arr = [
            'idtype' => $idtype,
            'typename' => $typename
        ];

        array_push($type_list, $arr);
    }

    $json = json_encode($type_list);
    echo $json;

}
?>