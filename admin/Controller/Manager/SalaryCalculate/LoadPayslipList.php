<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id = isset($_GET['idNV']) ? $_GET['idNV'] : null;
    $get_payslip = mysqli_query($conn, 'SELECT idLUONG, TONGTIEN, THANG, NAM, TINHTRANG from bangluong WHERE idNV=' . intval($id) . " ORDER BY NAM ASC, THANG ASC") ;
    //Khỏi tạo mảng pây lịp
    $listpayslip = array();
    
    while($slips = mysqli_fetch_array($get_payslip)){
        $idPL = $slips['idLUONG'];
        $month = $slips['THANG'];
        $year = $slips['NAM'];
        $total = $slips['TONGTIEN'];
        $status = $slips['TINHTRANG'];

        $arr = array(
            'idPL' => $idPL,
            'month' => $month,
            'year' => $year,
            'total' => $total,
            'status' => $status
        );

        array_push($listpayslip, $arr);
    }

    $json = json_encode($listpayslip);
    echo $json;
}  
?>