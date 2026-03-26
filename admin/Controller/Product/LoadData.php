<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $type = isset($_GET['type'])? $_GET['type'] : '';
    $sql = '';
    $list = array();

    if($type == "Hang"){
        $sql = "SELECT * from hang WHERE TRANGTHAI=1";
        $result = mysqli_query($conn, $sql);

        while($hang = mysqli_fetch_array($result)){
            $idhang = $hang['idHANG'];
            $tenhang = $hang['TENHANG'];
    
            $arr = ['id' => $idhang,
                'name' => $tenhang
            ];
    
            array_push($list, $arr);
        }
    }elseif ($type == "Danhmuc"){
        $sql = "SELECT * from danhmuc WHERE TRANGTHAI=1";
        $result = mysqli_query($conn, $sql);

        while($dm = mysqli_fetch_array($result)){
            $iddm = $dm['idDM'];
            $tendm = $dm['LOAISP'];
    
            $arr = ['id' => $iddm,
                'name' => $tendm
            ];
    
            array_push($list, $arr);
        }
    } 

    $json = json_encode($list);
    echo $json;
}
?>