<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $id = isset($data['id']) ? $data['id'] : null;

    $get_time = mysqli_query($conn, 'SELECT NGAYLAM, HESO from bangchamcong WHERE idNV=' . intval($id));

    $list_time = array();
    
    while($timekeep = mysqli_fetch_array($get_time)){
        $ngaylam = $timekeep['NGAYLAM'];
        $heso = $timekeep['HESO'];

        $arr = array(
            'ngaylam' => $ngaylam,
            'heso' => $heso,
        );

        array_push($list_time, $arr);
    }

    $json = json_encode($list_time);
    echo $json;
}   
?>