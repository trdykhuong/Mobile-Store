<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $type = isset($_GET['type'])? $_GET['type'] : '';

    if($type=='NCC'){
        $sql = "SELECT idNCC, TENNCC from nhacungcap WHERE TRANGTHAI=1";

        $result = mysqli_query($conn, $sql);
        $list_ncc = array();

        while($type = mysqli_fetch_array($result)){
            $id = $type['idNCC'];
            $name = $type['TENNCC'];
    
            $arr = array(
                'id' => $id,
                'name' => $name
            );
    
            array_push($list_ncc, $arr);
        }
        //Trả về list ncc
        $json = json_encode($list_ncc);
        echo $json;

    }else if($type=='HANG'){
        $sql = "SELECT idHANG, TENHANG from hang WHERE TRANGTHAI=1";

        $result = mysqli_query($conn, $sql);
        $list_hang = array();

        while($type = mysqli_fetch_array($result)){
            $id = $type['idHANG'];
            $name = $type['TENHANG'];
    
            $arr = array(
                'id' => $id,
                'name' => $name
            );
    
            array_push($list_hang, $arr);
        }
        //Trả về list hãng
        $json = json_encode($list_hang);
        echo $json;
    }else if($type=='DANHMUC'){
        $sql = "SELECT idDM, LOAISP from danhmuc WHERE TRANGTHAI=1";

        $result = mysqli_query($conn, $sql);
        $list_danhmuc = array();

        while($type = mysqli_fetch_array($result)){
            $id = $type['idDM'];
            $name = $type['LOAISP'];
    
            $arr = array(
                'id' => $id,
                'name' => $name
            );
    
            array_push($list_danhmuc, $arr);
        }
        //Trả về list danh mục
        $json = json_encode($list_danhmuc);
        echo $json;
    }
    else {
        $sql = "SELECT DISTINCT sp.idSP, sp.TENSP 
                FROM sanpham sp
                JOIN chitietsanpham ctsp ON sp.idSP = ctsp.idSP
                WHERE sp.TRANGTHAI=1 AND ctsp.TONKHO <=10";

        $result = mysqli_query($conn, $sql);
        $list_sp = array();

        while($type = mysqli_fetch_array($result)){
            $id = $type['idSP'];
            $name = $type['TENSP'];
    
            $arr = array(
                'id' => $id,
                'name' => $name
            );
    
            array_push($list_sp, $arr);
        }
        //Trả về list sp
        $json = json_encode($list_sp);
        echo $json;
    }

}
?>