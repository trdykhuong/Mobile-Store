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

    $id = isset($data['idNV']) ? $data['idNV'] : null;
    $month = isset($data['month']) ? $data['month'] : null;
    $year = isset($data['year']) ? $data['year'] : null;
    $phucap = isset($data['phucap']) ? doubleval($data['phucap']) : 0;
    $khautru = isset($data['khautru']) ? doubleval($data['khautru']) : 0;
    $tongtien = isset($data['tongtien']) ? doubleval($data['tongtien']) : 0;
    $ghichuPC = isset($data['ghichuPC']) ? $data['ghichuPC'] : null;
    $ghichuKT = isset($data['ghichuKT']) ? $data['ghichuKT'] : null;
    $tamung = isset($data['tamung']) ? doubleval($data['tamung']) : null;
    $tinhtrang = isset($data['tinhtrang']) ? $data['tinhtrang'] : null;
    $luongcb = isset($data['luongcb']) ? doubleval($data['luongcb']) : 0;
    $chucvu = isset($data['chucvu']) ? $data['chucvu'] : '';
    $luongchinh = isset($data['luongchinh']) ? doubleval($data['luongchinh']) : 0;

    //Check ngày tháng trc khi làm mọi thứ
    //Check ngày tháng coi hộp lý chưa
    $check_ngaythang = mysqli_query($conn, "SELECT DATE_FORMAT(NGAYVAOLAM, '%m') AS THANG, DATE_FORMAT(NGAYVAOLAM, '%Y') AS NAM FROM nhanvien WHERE idTK=$id");
    $ngaythang = mysqli_fetch_assoc($check_ngaythang);

    if(intval($year) < intval($ngaythang['NAM'])){
        MakeResponse('Chưa dô làm mà đòi ấy phiếu lương ?!', false);
    }

    if(intval($month) < intval($ngaythang['THANG'])){
        MakeResponse('Chưa dô làm mà đòi ấy phiếu lương ?!', false);
    }

    //Tạo response
    $message = 'Cập nhật phiếu lương thành kong';  
    $status = false;

    if($month < 10) $month = '0'. $month; //Tháng lưu đủ 2 số

    //Check đã có phiếu lương chưa
    $sql_check = "SELECT idLUONG from bangluong WHERE idNV='$id' AND THANG='$month' AND NAM='$year'";
    $check = mysqli_query($conn, $sql_check);
    $check_rows = mysqli_num_rows($check);

    if($check_rows <= 0){
        mysqli_query($conn, "INSERT INTO bangluong (idNV, THANG, NAM) VALUES
        ($id, '$month', '$year')");
    }

    //Chèn xong thì update nè
    $sql = "UPDATE bangluong SET PHUCAP=$phucap, KHAUTRU=$khautru, TONGTIEN=$tongtien, GHICHUPC='$ghichuPC',
    GHICHUKT='$ghichuKT', TAMUNG=$tamung, TINHTRANG=$tinhtrang, LUONGHIENTAI=$luongcb, CHUCVUHIENTAI='$chucvu', LUONGCHINH=$luongchinh
    WHERE idNV=$id AND THANG='$month' AND NAM='$year'";

    //Chạy sql r ấy kết quả thôi, cơ bản
    if(!mysqli_query($conn, $sql)){
        $message = 'Lỗi cái j đó r';
    }
    //Này cx cơ bản
    MakeResponse($message, $status);
}

mysqli_close($conn);
?>