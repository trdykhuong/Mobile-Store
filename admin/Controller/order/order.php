<?php
include_once(__DIR__ . "/../../../Model/db_connect.php");

$db = new Database();
$conn = $db->connect(); // Sử dụng $conn thay vì $connect

// Lấy trạng thái từ request
$status = isset($_GET["status"]) ? $_GET["status"] : "all";

// Truy vấn SQL
$sql = "SELECT d.idHD, tk.USERNAME AS khachhang, d.NGAYMUA, d.THANHTIEN, t.STATUS 
        FROM donhang d 
        JOIN taikhoan tk ON d.idTK = tk.idTK
        JOIN trangthaidonhang t ON d.TRANGTHAI = t.idSTATUS";

if ($status !== "all") {
    $sql .= " WHERE d.TRANGTHAI = " . intval($status);
}

// Thêm ORDER BY để sắp xếp theo idHD tăng dần
$sql .= " ORDER BY d.NGAYMUA DESC";

$result = $conn->query($sql); // Sửa $connect thành $conn
$orders = [];

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

// Trả về JSON
header('Content-Type: application/json');
echo json_encode($orders);

$conn->close(); // Sửa $connect thành $conn
?>