<?php
// Kết nối cơ sở dữ liệu
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");

header('Content-Type: application/json');
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Kiểm tra nếu có dữ liệu POST được gửi lên
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ POST
    $json = file_get_contents('php://input');   
    $data = json_decode($json, true);

    $lydo = isset($data['lydo'])? $data["lydo"] : '';
    $idnv = isset($data['idNV'])? $data["idNV"] : 0;
    $ngaynghi = isset($data['ngaynghi'])? $data["ngaynghi"] : '';

    // Thực hiện câu lệnh INSERT để lưu yêu cầu nghỉ phép vào cơ sở dữ liệu
    $stmt = mysqli_prepare($conn, "INSERT INTO ngaynghi (idNV, NGAYNGHI, LYDO, TRANGTHAI) VALUES (?, ?, ?, 'Đã duyệt')");
    mysqli_stmt_bind_param($stmt, "sss", $idnv, $ngaynghi, $lydo);

    if (mysqli_stmt_execute($stmt)) {
        $response = ['status' => 'success', 'message' => 'Yêu cầu nghỉ phép đã được gửi.'];
    } else {
        $response = ['status' => 'error', 'message' => 'Không thể nghỉ phép: ' . mysqli_error($conn)];
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    echo json_encode($response);
}
?>
