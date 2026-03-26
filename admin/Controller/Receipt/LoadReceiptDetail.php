<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $idPN = isset($data['idPN'])? $data['idPN'] : '';
    //Móc ra sản phẩm trong phiếu nhập
    $sql = 'SELECT sp.TENSP, ct.SOLUONG 
    FROM `chitietphieunhap` ct JOIN phieunhap pn ON 
    ct.idPN = pn.idPN JOIN chitietsanpham c ON ct.idCTSP = c.idCTSP JOIN sanpham sp ON c.idSP = sp.idSP WHERE pn.idPN=' . intval($idPN);

    $result = mysqli_query($conn, $sql);
    $list_details= array();
    
    //Chạy truy vấn lưu từng sp vào mảng
    while($details = mysqli_fetch_array($result)){
        $tensp = $details['TENSP'];
        $soluong = $details['SOLUONG'];

        $arr = array(
            'tensp' => $tensp,
            'soluong' => $soluong
        );

        array_push($list_details, $arr);
    }

    $json = json_encode($list_details);
    echo $json;
}
?>