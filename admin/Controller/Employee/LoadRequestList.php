<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $id = isset($data['idNV']) ? $data['idNV'] : null;
    $month = isset($data['month']) ? $data['month'] : '';
    $year = isset($data['year']) ? $data['year'] : '';

    $sql = 'SELECT NGAYGUI, NGAYNGHI, LYDO, TRANGTHAI FROM `ngaynghi` WHERE idNV=' . intval($id);
    
    //Khỏi tạo mảng pây lịp
    if($month != '' && $year != ''){
        $sql .= ' AND DATE_FORMAT(NGAYNGHI, "%Y")="'. $year .'" AND DATE_FORMAT(NGAYNGHI, "%m")="0' . $month . '" AND TRANGTHAI="Đã duyệt"';
    }

    $get_leave_request = mysqli_query($conn, $sql);
    $listleave_request = array();
    
    while($leaves = mysqli_fetch_array($get_leave_request)){
        $ngaygui = $leaves['NGAYGUI'];
        $ngaynghi = $leaves['NGAYNGHI'];
        $lydo = $leaves['LYDO'];
        $trangthai = $leaves['TRANGTHAI'];

        $arr = array(
            'ngaygui' => $ngaygui,
            'ngaynghi' => $ngaynghi,
            'trangthai' => $trangthai,
            'lydo' => $lydo
        );

        array_push($listleave_request, $arr);
    }

    $json = json_encode($listleave_request);
    echo $json;
}  
?>