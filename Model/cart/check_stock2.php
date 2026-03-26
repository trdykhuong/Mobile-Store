<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kết nối DB
$connect = new mysqli("localhost", "root", "", "chdidong");
if ($connect->connect_error) {
    die("Lỗi kết nối: " . $connect->connect_error);
}

// Kiểm tra xem có idCTSP được gửi không
if (isset($_GET['idCTSP'])) {
    $id = intval($_GET['idCTSP']);

    $stmt = $connect->prepare("SELECT TONKHO FROM chitietsanpham WHERE idCTSP = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($stock);
    $stmt->fetch();
    $stmt->close();

    if ($stock !== null) {
        echo json_encode(["success" => true, "stock" => (int)$stock]);
    } else {
        echo json_encode(["success" => false, "message" => "Không tìm thấy sản phẩm", "stock" => 0]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Thiếu idCTSP", "stock" => 0]);
}

$connect->close();
?>
