<?php
include_once(__DIR__ . "/../../../Model/db_connect.php");

$db = new Database();
$conn = $db->connect(); // Sử dụng $conn thay vì $connect
$idHD = isset($_GET['idHD']) ? intval($_GET['idHD']) : 0;
if ($idHD <= 0) {
    die("ID đơn hàng không hợp lệ.");
}

// Lấy thông tin đơn hàng
$sql = "SELECT d.idHD, tk.USERNAME AS khachhang, d.NGAYMUA, d.THANHTIEN, d.DIACHI, t.idSTATUS, t.STATUS 
        FROM donhang d 
        JOIN taikhoan tk ON d.idTK = tk.idTK
        JOIN trangthaidonhang t ON d.TRANGTHAI = t.idSTATUS
        WHERE d.idHD = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $idHD);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Đơn hàng không tồn tại.");
}

// Lấy danh sách sản phẩm trong đơn hàng
$sql_products = "SELECT sp.TENSP, cthd.SOLUONG, sp.GIA ,sp.IMG, ctsp.MAUSAC, ctsp.DUNGLUONG
                 FROM chitiethoadon cthd
                 JOIN chitietsanpham ctsp ON cthd.idCTSP = ctsp.idCTSP
                 JOIN sanpham sp ON ctsp.idSP = sp.idSP
                 WHERE cthd.idHD = ?";
$stmt_products = $connect->prepare($sql_products);
$stmt_products->bind_param("i", $idHD);
$stmt_products->execute();
$products = $stmt_products->get_result();

// Xử lý cập nhật trạng thái đơn hàng
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $status = intval($_POST['status']);
    $sql_update = "UPDATE donhang SET TRANGTHAI = ? WHERE idHD = ?";
    $stmt_update = $connect->prepare($sql_update);
    $stmt_update->bind_param("ii", $status, $idHD);

    if ($stmt_update->execute()) {
        echo "<script>alert('Cập nhật trạng thái thành công!'); window.location.href='?page=orderdetail&idHD=$idHD';</script>";
    } else {
        echo "<script>alert('Lỗi khi cập nhật trạng thái.');</script>";
    }
}

$connect->close();
?>
