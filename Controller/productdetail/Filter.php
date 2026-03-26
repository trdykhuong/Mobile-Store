<?php

$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $sql_queue = isset($data['sql']) ? $data['sql'] : '';
    $sql_order = isset($data['sql_order']) ? $data['sql_order'] : '  GROUP BY sp.idSP ';
    $limit = isset($data['limit']) ? ' LIMIT ' . $data['limit'] : '';
    $offset = isset($data['offset']) ? ' OFFSET ' . $data['offset'] : '';

    //Ghép câu truy vấn 
    $sql = "SELECT sp.idSP, sp.TENSP, sp.GIA, sp.IMG, sp.DISCOUNT, COUNT(hd.idCTSP) AS SOLUONG FROM sanpham sp 
    LEFT JOIN chitietsanpham ctsp ON sp.idSP=ctsp.idSP
    JOIN hang h ON sp.HANG=h.idHANG

    LEFT JOIN chitiethoadon hd ON CTSP.idCTSP=hd.idCTSP WHERE sp.TRANGTHAI=1 " . $sql_queue . $sql_order . $limit . $offset;
    
    $result = mysqli_query($conn, $sql);
    $list_products = array();

    while($sp_rows = mysqli_fetch_assoc($result)){
        $idsp = $sp_rows['idSP'];
        $tensp = $sp_rows['TENSP'];
        $gia = $sp_rows['GIA'];
        $img = $sp_rows['IMG'];
        $discount = $sp_rows['DISCOUNT'];
        //Lấy ra số trung bình rating của sp
        $sql_rate = "SELECT AVG(r.rating) AS RATE FROM sanpham sp JOIN rating r ON
        sp.idSP=r.idSP WHERE sp.idSP=$idsp GROUP BY sp.idSP ";
        $result_rate = mysqli_query($conn, $sql_rate);

        $rate = 0;
        while($rate_rows = mysqli_fetch_assoc($result_rate)){
            $rate = intval($rate_rows['RATE']);
        }

        $arr = array(
            'idsp' => $idsp,
            'tensp' => $tensp,
            'gia' => $gia,
            'img' => $img,
            'discount' => $discount,
            'rate' => $rate
        );

        array_push($list_products, $arr);
    }

    $json = json_encode($list_products);
    echo $json;
}
?>