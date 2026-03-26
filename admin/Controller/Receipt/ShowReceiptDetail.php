<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $idPN = isset($_GET['idPN'])? $_GET['idPN'] : '';

    //Móc ra sản phẩm trong phiếu nhập
    $sql = 'SELECT sp.TENSP, sp.IMG, c.MAUSAC, c.DUNGLUONG, ct.GIANHAP, ct.DIEUCHINHGIA, c.TONKHO, sp.GIA
    FROM `chitietphieunhap` ct JOIN phieunhap pn ON 
    ct.idPN = pn.idPN JOIN chitietsanpham c ON ct.idCTSP = c.idCTSP JOIN sanpham sp ON c.idSP = sp.idSP WHERE pn.idPN=' . intval($idPN);

    $result = mysqli_query($conn, $sql);
    $list_details= array();
    //Chạy truy vấn lưu từng sp vào mảng
    while($details = mysqli_fetch_array($result)){
        $tensp = $details['TENSP'];
        $soluong = $details['TONKHO'];
        $gianhap = $details['GIANHAP'];
        $giaban = $details['DIEUCHINHGIA'] + $details['GIA'];
        $img = $details['IMG'];
        $dungluong = $details['DUNGLUONG'];
        $mausac = $details['MAUSAC'];

        $arr = array(
            'tensp' => $tensp,
            'soluong' => $soluong,
            'gianhap' => $gianhap,
            'giaban' => $giaban,
            'img' => $img,
            'dungluong' => $dungluong,
            'mausac' => $mausac
        );

        array_push($list_details, $arr);
    }

    $json = json_encode($list_details);
    echo $json;
}
?>