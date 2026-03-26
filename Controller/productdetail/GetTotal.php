<?php

$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $sql_queue = isset($data['sql']) ? $data['sql'] : '';
    $sql_order = isset($data['sql_order']) ? $data['sql_order'] : '  GROUP BY sp.idSP ';

    //Ghép câu truy vấn 
    $sql = "SELECT sp.idSP, sp.TENSP, sp.GIA, sp.IMG, sp.DISCOUNT, COUNT(hd.idCTSP) AS SOLUONG FROM sanpham sp
    LEFT JOIN chitietsanpham ctsp ON sp.idSP=ctsp.idSP
     
    LEFT JOIN chitiethoadon hd ON ctsp.idCTSP=hd.idCTSP WHERE sp.TRANGTHAI=1 " . $sql_queue . $sql_order;
    
    $result = mysqli_query($conn, $sql);
    
    $product_rows = mysqli_num_rows($result);

    $json = json_encode($product_rows);
    echo $json;
}
?>