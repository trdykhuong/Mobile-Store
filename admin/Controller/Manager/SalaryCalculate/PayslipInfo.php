<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $id = isset($data['idNV']) ? $data['idNV'] : null;
    $month = isset($data['month']) ? $data['month'] : null;
    $year = isset($data['year']) ? $data['year'] : null;

     //Check ngày tháng coi hộp lý chưa
    $check_ngaythang = mysqli_query($conn, "SELECT DATE_FORMAT(NGAYVAOLAM, '%m') AS THANG, DATE_FORMAT(NGAYVAOLAM, '%Y') AS NAM FROM nhanvien WHERE idTK=$id");
    $ngaythang = mysqli_fetch_assoc($check_ngaythang);

    if(intval($year) < intval($ngaythang['NAM'])){
        $response = [
            'status' => false,
            'message' => 'Năm này nhân viên chưa dô làm'
        ];

        $json = json_encode($response);
        echo $json;
        exit;
    }
    
    if(intval($month) < intval($ngaythang['THANG'])){
        $response = [
            'status' => false,
            'message' => 'Tháng này nhân viên chưa dô làm'
        ];

        $json = json_encode($response);
        echo $json;
        exit;
    }

    if($month < 10) $month = '0'. $month; //Tháng lưu đủ 2 số

    //Check đã có phiếu lương chưa
    $sql_check = "SELECT idLUONG from bangluong WHERE idNV='$id' AND THANG='$month' AND NAM='$year'";
    $check = mysqli_query($conn, $sql_check);
    $check_rows = mysqli_num_rows($check);

    if($check_rows <= 0){
        mysqli_query($conn, "INSERT INTO bangluong (idNV, THANG, NAM) VALUES
        ($id, '$month', '$year')");
    }

    //Chèn xong thì móc
    $sql = "SELECT idLUONG, GHICHUPC, GHICHUKT, TINHTRANG, TAMUNG, LUONGHIENTAI, CHUCVUHIENTAI from bangluong 
    WHERE idNV=$id AND THANG='$month' AND NAM='$year'";

    //Chạy sql r ấy kết quả thôi, cơ bản
    $result = mysqli_query($conn, $sql);

    while($pl = mysqli_fetch_assoc($result)){
        $idluong = $pl['idLUONG'];
        $ghichuPC = $pl['GHICHUPC'];
        $ghichuKT = $pl['GHICHUKT'];
        $luong = $pl['LUONGHIENTAI'];
        $chucvu = $pl['CHUCVUHIENTAI'];
        $tamung = $pl['TAMUNG'];
        $tinhtrang = $pl['TINHTRANG'];

        //Này cx cơ bản
        $status = true;
        $response = [
            'status' => $status,
            'idluong' => $idluong,
            'ghichuPC' => $ghichuPC,
            'ghichuKT' => $ghichuKT,
            'luong' => $luong,
            'chucvu' => $chucvu,
            'tamung' => $tamung,
            'tinhtrang' => $tinhtrang,    
        ];

        $json = json_encode($response);
        echo $json;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $idPL = isset($_GET['idLUONG']) ? $_GET['idLUONG'] : null;
    
    $sql = "SELECT * from bangluong WHERE idLUONG=$idPL";

    //Chạy sql r ấy kết quả thôi, cơ bản
    $result = mysqli_query($conn, $sql);

    while($pl = mysqli_fetch_assoc($result)){
        $thang = $pl['THANG'];
        $nam = $pl['NAM'];
        $ghichuPC = $pl['GHICHUPC'];
        $ghichuKT = $pl['GHICHUKT'];
        $phucap = $pl['PHUCAP'];
        $khautru = $pl['KHAUTRU'];
        $chucvu = $pl['CHUCVUHIENTAI'];
        $luong = $pl['LUONGHIENTAI'];
        $tamung = $pl['TAMUNG'];
        $tinhtrang = $pl['TINHTRANG'];
        $tongtien = $pl['TONGTIEN'];

        //Này cx cơ bản
        $response = [
            'thang' => $thang,
            'nam' => $nam,
            'phucap' => $phucap,
            'khautru' => $khautru,
            'ghichuPC' => $ghichuPC,
            'ghichuKT' => $ghichuKT,
            'luong' => $luong,
            'chucvu' => $chucvu,
            'tamung' => $tamung,
            'tinhtrang' => $tinhtrang,  
            'tongtien' => $tongtien
        ];

        $json = json_encode($response);
        echo $json;
    }
}

mysqli_close($conn);
?>