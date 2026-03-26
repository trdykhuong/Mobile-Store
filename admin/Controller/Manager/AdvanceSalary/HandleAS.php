<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');   
    $data = json_decode($json, true);

    $idluong = isset($data['idluong'])? $data["idluong"] : '';
    $tamung = isset($data['tamung'])? $data["tamung"] : '';
    $trangthaimoi = isset($data['trangthaimoi'])? $data["trangthaimoi"] : '';

    //Xử lý tùy theo option
    switch($trangthaimoi){
        case 'Duyệt': {
            $sql= "UPDATE bangluong SET TAMUNG=" . doubleval($tamung) . " WHERE idLUONG=$idluong"; 
            $sql2= "UPDATE ungluong SET TRANGTHAI='$trangthaimoi' WHERE idLUONG=$idluong"; 
            $message="Duyệt thành công"; 

            //Chạy truy vấn
            mysqli_query($conn, $sql);
            mysqli_query($conn, $sql2);

            break;
        }
        case 'Bị từ chối':{
            $sql = "UPDATE ungluong SET TRANGTHAI='$trangthaimoi' WHERE idLUONG=$idluong";
            //Chạy truy vấn
            mysqli_query($conn, $sql);
            $message = "Từ chối thành công";
            break;
        }
        default: $message = "Chịu á mom, đã làm j đâu"; break;
    }

    //đóng hòm, à nhầm đóng gói message thoai
    $response= ['message' => $message];

    $json = json_encode($response);
    echo $json;

}
?>