<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');   
    $data = json_decode($json, true);

    $option = isset($data['option'])? $data["option"] : '';
    $idnv = isset($data['idNV'])? $data["idNV"] : '';
    $ngaynghi = isset($data['ngaynghi'])? $data["ngaynghi"] : '';

    $sql = '';
    $message = '';

    //Xử lý tùy theo option
    switch($option){
        case 'duyet': {
            $sql= "UPDATE ngaynghi SET TRANGTHAI='Đã duyệt' WHERE idnv=".$idnv." AND NGAYNGHI='" .$ngaynghi. "'"; 
            $message="Duyệt thành công"; 
            break;
        }
        case 'tuchoi':{
            $sql = "UPDATE ngaynghi SET TRANGTHAI='Đã từ chối' WHERE idnv=".$idnv." AND NGAYNGHI='" .$ngaynghi. "'";
            $message = "Đã hủy đơn xin nghỉ phép";
            break;
        }
        default: $message = "Chịu á mom, đã làm j đâu"; break;
    }
    //Chạy truy vấn và đóng hòm, à nhầm đóng gói message thoai
    $result = mysqli_query($conn, $sql);
    $response= ['message' => $message];

    $json = json_encode($response);
    echo $json;
}
?>