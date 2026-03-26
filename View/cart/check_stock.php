<?php
require_once '../../Model/db_connect.php';

$db = new Database();
$db->connect();

if (isset($_GET['idCTSP'])) {
    $id = intval($_GET['idCTSP']);
    $stmt = $db->prepare("SELECT TONKHO FROM chitietsanpham WHERE idCTSP = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(["success" => true, "stock" => (int)$row['TONKHO']]);
    } else {
        echo json_encode(["success" => false, "message" => "Không tìm thấy sản phẩm", "stock" => 0]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Thiếu idCTSP", "stock" => 0]);
}

$db->close();
?>
