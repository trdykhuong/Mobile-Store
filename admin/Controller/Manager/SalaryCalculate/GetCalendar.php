<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $id = isset($data['idNV']) ? $data['idNV'] : null;
    $month = isset($data['month']) ? $data['month'] : null;
    $year = isset($data['year']) ? $data['year'] : null;

    if($month < 10) $month = '0'. $month;

    $sql = 'SELECT DATE_FORMAT(b.NGAYLAM, "%d") AS NGAYLAM, b.idNGAYLE AS LE, l.NGAY AS NGAYLE, l.TENNGAYLE from bangchamcong b LEFT JOIN ngayle l ON b.idNGAYLE=l.idNGAYLE WHERE b.idNV=' . intval($id). 
    ' AND DATE_FORMAT(b.NGAYLAM, "%Y")="'. $year .'" AND DATE_FORMAT(b.NGAYLAM, "%m")="' . $month . '" GROUP BY NGAYLAM';

    $get_calendar = mysqli_query($conn, $sql);
    
    $ngaylist = array();
    while($ca = mysqli_fetch_assoc($get_calendar)){
        $day = $ca['NGAYLAM'];
        $le = $ca['LE'];
        $ngayle = $ca['NGAYLE'];
        $tenngayle = $ca['TENNGAYLE'];

        ///Lấy ra số giờ làm trong ngày
        $hour = array();

        $sub_sql = "SELECT SEC_TO_TIME(CHECKOUT - CHECKIN) AS HOUR, ((CHECKOUT - CHECKIN) / 3600) AS TOTAL, HESO from bangchamcong WHERE NGAYLAM='$year-$month-$day' AND idNV='$id'";
        $getTime = mysqli_query($conn, $sub_sql);
        while($time  = mysqli_fetch_assoc($getTime)){
            
            $hour_time =[ 
                'hour' => $time['HOUR'],
                'total' => $time['TOTAL'],
                'heso' => $time['HESO']
            ];

            array_push($hour, $hour_time);
        }
          ///Lấy ra số giờ làm trong ngày

        $arr = [
            'ngay' => $day,
            'totalHour' => $hour,
            'le' => $le,
            'ngayle' => $ngayle,
            'tenngayle' => $tenngayle
        ];
    
        array_push($ngaylist, $arr);
    }

    $json = json_encode($ngaylist);
    echo $json;
}  

mysqli_close($conn);

?>