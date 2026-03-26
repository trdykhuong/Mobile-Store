<?php
    function capNhatPhanQuyen($idQuyen, $dsQuyen) {
        include_once(__DIR__ . "/../../../Model/db_connect.php");
        $db = new Database();
        $conn = $db->connect();

        // Xóa hết quyền cũ của vai trò này trước khi cập nhật lại
        $sqlDelete = "DELETE FROM phanquyen WHERE idQUYEN = ?";
        $stmt = $conn->prepare($sqlDelete);
        $stmt->bind_param("i", $idQuyen);
        $stmt->execute();
        $stmt->close();

        // Nếu không có quyền nào được chọn, thì thoát luôn
        if (empty($dsQuyen)) {
            return;
        }

        // Chèn lại các quyền mới
        $sqlInsert = "INSERT INTO phanquyen (idQUYEN, idCN, THAOTAC) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sqlInsert);

        foreach ($dsQuyen as $quyen) {
            list($idCN, $thaotac) = explode('_', $quyen);
            $stmt->bind_param("iis", $idQuyen, $idCN, $thaotac);
            $stmt->execute();
        }

        $stmt->close();
        $conn->close();
    }
?>