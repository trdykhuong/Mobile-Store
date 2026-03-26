<?php
    include_once(__DIR__ . "/../../../Model/db_connect.php");

function themQuyenMoi($tenquyen) {
    $db = new Database();
    $conn = $db->connect();

    // Kiểm tra xem tên quyền đã tồn tại chưa
    $sqlCheck = "SELECT idQUYEN FROM QUYEN WHERE TENQUYEN = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $tenquyen);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        $stmtCheck->close();
        $conn->close();
        return false; // Quyền đã tồn tại
    }

    // Thêm quyền mới
    $sqlInsert = "INSERT INTO QUYEN (TENQUYEN) VALUES (?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("s", $tenquyen);
    $stmtInsert->execute();

    $new_role_id = $stmtInsert->insert_id;

    $stmtInsert->close();
    $conn->close();

    return $new_role_id;
}

function themPhanQuyen($idQUYEN, $idCN, $thaotac) {
    $db = new Database();
    $conn = $db->connect();

    $sql = "INSERT INTO PHANQUYEN (idQUYEN, idCN, THAOTAC) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $idQUYEN, $idCN, $thaotac);
    $stmt->execute();

    $stmt->close();
    $conn->close();
}
?>