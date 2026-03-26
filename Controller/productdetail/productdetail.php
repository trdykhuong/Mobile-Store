<?php
include __DIR__ . "/../../Model/db_connect.php";

$db = new Database();
$db->connect();

if (isset($_GET['id'])) {
    $idSP = $_GET['id'];
} else {
    $idSP = 0;
}

$check_issetidCTSP = "SELECT idCTSP FROM chitietsanpham WHERE idSP = ?";
$check_issetidCTSP_stmt = $db->prepare($check_issetidCTSP);
$check_issetidCTSP_stmt->bind_param("i", $idSP);
$check_issetidCTSP_stmt->execute();

// Lấy kết quả từ câu truy vấn
$result_idCTSP = $check_issetidCTSP_stmt->get_result();

if ($result_idCTSP->num_rows <= 0) {
    // Nếu không tồn tại idCTSP cho idSP này, thực hiện INSERT
    $insert_query = "INSERT INTO chitietsanpham (idSP, MAUSAC, DUNGLUONG, DIEUCHINHGIA, TONKHO, IMG) VALUES (?, 'Trắng', 'KHÔNG CÓ', 0, 2, '.jpg')";
    $insert_stmt = $db->prepare($insert_query);

    $insert_stmt->bind_param("i", $idSP);
    if ($insert_stmt->execute()) {
        echo "Đã thêm mới chi tiết sản phẩm cho idSP = " . $idSP;
    } else {
        echo "Lỗi khi thêm chi tiết sản phẩm: " . $insert_stmt->error;
    }

    $insert_stmt->close();
} 
// Đóng statement
$check_issetidCTSP_stmt->close();

$query = "SELECT * FROM sanpham WHERE idSP = $idSP AND TRANGTHAI = 1";
$result = $db->execute($query);
$sanpham = $db->getData();

// $query1 = "SELECT s.*, 
//                  GROUP_CONCAT(DISTINCT ct.mau_sac) AS mau_sac, 
//                  GROUP_CONCAT(DISTINCT ct.dung_luong) AS dung_luong 
//           FROM sanpham s
//           JOIN chitietsanpham ct ON s.idSP = ct.id_san_pham
//           WHERE ct.id = ? AND s.TRANGTHAI = 1
//           GROUP BY s.idSP";

// $stmt = $db->prepare($query1);
// $stmt->bind_param("i", $idSP);
// $stmt->execute();
// $result = $stmt->get_result();

// if ($result->num_rows > 0) {
//     $sanpham = $result->fetch_assoc();
//     // Chuyển đổi chuỗi `mau_sac` và `dung_luong` thành mảng
//     $sanpham['mau_sac'] = explode(',', $sanpham['mau_sac']);
//     $sanpham['dung_luong'] = explode(',', $sanpham['dung_luong']);
// } else {
//     $sanpham = null; // Không tìm thấy sản phẩm
// }

// $stmt->close();

require_once("../../View/showproduct/productdetail.php");
?>