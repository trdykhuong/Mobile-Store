<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    //Móc ra ấy
    $sql = "SELECT u.TIENTAMUNG, u.LYDO, u.TRANGTHAI, b.idLUONG, t.HOTEN FROM ungluong u
            JOIN bangluong b ON u.idLUONG=b.idLUONG JOIN taikhoan t ON b.idNV=t.idTK
            WHERE u.TRANGTHAI='Chưa duyệt'";
    $result = mysqli_query($conn, $sql);

    $response= array();
    //Chạy truy vấn lưu từng ấy vào mảng
    while($list = mysqli_fetch_array($result)){
        $hoten = $list['HOTEN'];
        $idluong = $list['idLUONG'];
        $tamung = $list['TIENTAMUNG'];
        $lydo = $list['LYDO'];
        $trangthai = $list['TRANGTHAI'];

        $arr = ['hoten' => $hoten,
            'idluong' => $idluong,
            'tamung' => $tamung,
            'lydo' => $lydo,
            'trangthai' => $trangthai
        ];

        array_push($response, $arr);
    }

    $json = json_encode($response);
    echo $json;
}
?>