<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $orderId = $_POST['idHD'];
    $status = $_POST['status'];

    $connect = new mysqli("localhost", "root", "", "chdidong");

    if ($connect->connect_error) {
        die("Connection failed: " . $connect->connect_error);
    }

    // Nếu trạng thái là hủy đơn hàng, hoàn lại số lượng sản phẩm
    if ($status == 5) { // 5 là trạng thái "Hủy đơn hàng"
        $stmt = $connect->prepare("SELECT idCTSP, SOLUONG FROM chitiethoadon WHERE idHD = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $idCTSP = $row['idCTSP'];
            $quantity = $row['SOLUONG'];

            // Hoàn lại số lượng sản phẩm
            $updateStmt = $connect->prepare("UPDATE chitietsanpham SET TONKHO = TONKHO + ? WHERE idCTSP = ?");
            $updateStmt->bind_param("ii", $quantity, $idCTSP);
            $updateStmt->execute();
            $updateStmt->close();
        }

        $stmt->close();
    }

    // Cập nhật trạng thái đơn hàng
    $stmt = $connect->prepare("UPDATE donhang SET TRANGTHAI = ? WHERE idHD = ?");
    $stmt->bind_param("ii", $status, $orderId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }

    $stmt->close();
    $connect->close();
}