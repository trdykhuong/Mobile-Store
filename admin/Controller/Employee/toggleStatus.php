<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$connect = new mysqli("localhost", "root", "", "chdidong");

if ($connect->connect_error) {
    die("Kết nối thất bại: " . $connect->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idTK'])) {
    $idTK = $_POST['idTK'];

    // Lấy trạng thái hiện tại
    $query = "SELECT TRANGTHAI FROM taikhoan WHERE idTK = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("i", $idTK);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $newStatus = ($user['TRANGTHAI'] == 1) ? 0 : 1; // Đảo trạng thái

        // Cập nhật trạng thái mới
        $updateQuery = "UPDATE taikhoan SET TRANGTHAI = ? WHERE idTK = ?";
        $stmt = $connect->prepare($updateQuery);
        $stmt->bind_param("ii", $newStatus, $idTK);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Lỗi cập nhật trạng thái!";
        }
    } else {
        echo "Không tìm thấy nhân viên!";
    }
}
?>
