<?php
header('Content-Type: application/json');
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");

function MakeResponse($message, $status){
    $response = [ 
        'message'=> $message,
        'status' => $status
    ];

    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $idNV = isset($data['idNV']) ? $data['idNV'] : null;
    $month = isset($data['month']) ? $data['month'] : null;
    $year = isset($data['year']) ? $data['year'] : null;
    $lydo = isset($data['lydo']) ? $data['lydo'] : null;
    $tienung = isset($data['tienung']) ? doubleval($data['tienung']) : null;
    
    //Check ngày tháng coi hộp lý chưa
    $check_ngaythang = mysqli_query($conn, "SELECT DATE_FORMAT(NGAYVAOLAM, '%m') AS THANG, DATE_FORMAT(NGAYVAOLAM, '%Y') AS NAM FROM nhanvien WHERE idTK=$idNV");
    $ngaythang = mysqli_fetch_assoc($check_ngaythang);

    if(intval($year) < intval($ngaythang['NAM'])){
        MakeResponse('Năm này chưa dô làm mà đòi ứng lương ?!', false);
    }

    if(intval($month) < intval($ngaythang['THANG'])){
        MakeResponse('Tháng này hưa dô làm mà đòi ứng lương ?!', false);
    }

    // if(intval($month) > intval($ngaythang['THANG'])){
    //     MakeResponse('Chỉ xin ứng được trong tháng thôi ?!', false);
    // }

    $datetime = date('m');
    if(intval($month) < intval($datetime)){
        MakeResponse('Tháng này qua rồi mẹ ?!', false);
    }
    //Check đã có phiếu lương chưa
    if($month < 10) $month = '0'. $month; //Tháng lưu đủ 2 số
    $check = mysqli_query($conn, "SELECT b.idLUONG from bangluong b WHERE b.idNV=$idNV AND b.THANG='$month' AND b.NAM='$year'");
    $check_rows = mysqli_num_rows($check);

    //Chưa có thì tạo mới bảng lương
    if($check_rows <= 0){
        mysqli_query($conn, "INSERT INTO bangluong (idNV, THANG, NAM) VALUES
        ($idNV, '$month', '$year')");
    }

    //Check nghiệp vụ lương
    $check_2 = mysqli_query($conn, "SELECT b.idLUONG, COUNT(b.idLUONG) AS SOLUONG, q.LUONGCB from bangluong b JOIN taikhoan tk 
    ON b.idNV=tk.idTK JOIN quyen q ON tk.idQUYEN=q.idQUYEN 
    WHERE b.idNV=$idNV AND b.THANG='$month' AND b.NAM='$year' GROUP BY b.idLUONG");

    $check_rows_2 = mysqli_fetch_assoc($check_2);
    //Lấy ra id bảng lương lưu vào cái yêu cầu ứng
    $idPL = $check_rows_2['idLUONG'];

    //Check coi tháng này đã ứng lương mấy lần r
    $quantity_check = mysqli_query($conn, "SELECT idLUONG from ungluong WHERE idLUONG=$idPL");
    if(mysqli_num_rows($quantity_check) > 0){
        MakeResponse( 'Tháng ứng 1 lần thoy mẹ', false);
    }

    //Check giá tiền ứng
    $tienung_check = 30 / 100 * ($check_rows_2['LUONGCB'] * 8 * 26);
    if($tienung > $tienung_check){
        MakeResponse("Tiền ứng không dc vượt quá 30% tổng lương (chưa bao gồm phụ cấp và thuế): " . intval($tienung_check) . " VND", false);
    }

    //chạy truy vấn thim dô bảng ứng lương
    if(!mysqli_query($conn, "INSERT INTO ungluong (idLUONG, TIENTAMUNG, LYDO) VALUES ($idPL, $tienung, '$lydo')")){
        MakeResponse('HÌnh như có cái j đó sai sai', false);
    }
    
   MakeResponse('Thim rồi, mướt mườn mượt. Chờ duyệt nghe ấy', true);
}
mysqli_close($conn);
?>