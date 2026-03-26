<?php
$conn = mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    //Móc ra nhân viên
    $sql = "SELECT n.idTK, t.HOTEN from ((SELECT idTK from nhanvien) AS n JOIN (SELECT idTK, HOTEN from taikhoan) AS t ON n.idTK = t.idTK)";

    ///Chạy sql
    $result = mysqli_query($conn, $sql);
    $employeelist = array();
    //Chạy truy vấn lưu từng nhân viên vào mảng
    while($nv = mysqli_fetch_array($result)){
        $idnv = $nv['idTK'];
        $tennv = $nv['HOTEN'];

        $arr = ['id' => $idnv,
            'name' => $tennv,
        ];

        array_push($employeelist, $arr);
    }

    $json = json_encode($employeelist);
    echo $json;
}
?>