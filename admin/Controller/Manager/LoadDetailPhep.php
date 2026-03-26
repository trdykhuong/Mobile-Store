<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $idnv  = isset($data['idNV'])? $data['idNV'] : 0;
    $ngaynghi  = isset($data['ngaynghi'])? $data['ngaynghi'] : "";

    //Móc ra chi tiết xin phép
    $sql = 'SELECT nn.NGAYNGHI, nn.LYDO, tk.HOTEN, nv.IMG FROM ngaynghi nn JOIN taikhoan tk ON nn.idNV = tk.idTK
     JOIN nhanvien nv ON nv.idTK = nn.idNV WHERE nn.NGAYNGHI="'.$ngaynghi. '" AND tk.idTK='. $idnv;
    
    $result = mysqli_query($conn, $sql);

    while($listnghi = mysqli_fetch_assoc($result)){
        $tennv = $listnghi['HOTEN'];
        $img = $listnghi['IMG'];
        $ngaynghi = $listnghi['NGAYNGHI'];
        $lydo = $listnghi['LYDO'];
    }
    
    $response = [
        'idnv' => $idnv,
        'tennv' => $tennv,
        'imgnv' => $img,
        'ngaynghi' => $ngaynghi,
        'lydo' => $lydo
    ];

    // $response = $sql;

    $json = json_encode($response);
    echo $json;
}
?>