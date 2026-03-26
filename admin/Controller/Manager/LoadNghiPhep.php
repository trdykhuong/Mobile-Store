<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    //Móc ra ấy
    $sql = 'SELECT nn.idNV, nn.NGAYNGHI, nn.TRANGTHAI, tk.HOTEN, nv.IMG FROM ngaynghi nn JOIN taikhoan tk ON nn.idNV = tk.idTK JOIN nhanvien nv ON nv.idTK = nn.idNV WHERE tk.TRANGTHAI=1 AND nn.TRANGTHAI=0';
    $result = mysqli_query($conn, $sql);

    $response= array();
    //Chạy truy vấn lưu từng ấy vào mảng
    while($listnghi = mysqli_fetch_array($result)){
        $idnv = $listnghi['idNV'];
        $tennv = $listnghi['HOTEN'];
        $img = $listnghi['IMG'];
        $ngaynghi = $listnghi['NGAYNGHI'];
        $trangthai = $listnghi['TRANGTHAI'];

        $arr = ['idnv' => $idnv,
            'tennv' => $tennv,
            'img' => $img,
            'ngaynghi' => $ngaynghi,
            'trangthai' => $trangthai
        ];

        array_push($response, $arr);
    }

    $json = json_encode($response);
    echo $json;
}
?>