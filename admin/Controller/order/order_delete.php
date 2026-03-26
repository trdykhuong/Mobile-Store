<?php
include_once(__DIR__ . "/../../../Model/db_connect.php");

if (isset($_GET['idHD'])) {
    $idHD = intval($_GET['idHD']); // Đảm bảo kiểu dữ liệu

    $db = new Database();
    $conn = $db->connect();

    // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
    $conn->begin_transaction();

    try {
        // Lấy danh sách sản phẩm và số lượng trong đơn hàng cần xóa
        $sql_get_products = "SELECT idCTSP, SOLUONG FROM chitiethoadon WHERE idHD = ?";
        $stmt = $conn->prepare($sql_get_products);
        $stmt->bind_param("i", $idHD);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        $stmt->close();

        // Cập nhật lại số lượng sản phẩm trong kho
        foreach ($products as $product) {
            $idSP = $product['idSP'];
            $soLuong = $product['TONKHO'];

            $sql_update_stock = "UPDATE chitietsanpham SET TONKHO = TONKHO + ? WHERE idCTSP = ?";
            $stmt = $conn->prepare($sql_update_stock);
            $stmt->bind_param("ii", $soLuong, $idSP);
            $stmt->execute();
            $stmt->close();
        }

        // Xóa chi tiết hóa đơn trước (do ràng buộc khóa ngoại)
        $sql_delete_details = "DELETE FROM chitiethoadon WHERE idHD = ?";
        $stmt = $conn->prepare($sql_delete_details);
        $stmt->bind_param("i", $idHD);
        $stmt->execute();
        $stmt->close();

        // Xóa hóa đơn chính
        $sql_delete_order = "DELETE FROM donhang WHERE idHD = ?";
        $stmt = $conn->prepare($sql_delete_order);
        $stmt->bind_param("i", $idHD);
        $stmt->execute();
        $stmt->close();

        // Commit transaction sau khi mọi thứ thành công
        $conn->commit();

        // Trả về kết quả thành công
        echo json_encode(['status' => 'success', 'message' => 'Đơn hàng đã được xóa thành công.']);
    } catch (Exception $e) {
        $conn->rollback(); // Có lỗi thì rollback lại
        echo json_encode(['status' => 'error', 'message' => 'Lỗi khi xóa đơn hàng: ' . $e->getMessage()]);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu ID hóa đơn cần xóa.']);
}