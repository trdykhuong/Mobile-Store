<?php
    include_once(__DIR__ . "/../../../Model/db_connect.php");

    $db = new Database();
    $conn = $db->connect();

    // Lấy danh sách các vai trò từ bảng quyen
    $sql_roles = "SELECT * FROM quyen WHERE TRANGTHAI = 1 AND idQUYEN <>0 AND idQUYEN <>1";
    $result_roles = $conn->query($sql_roles);
    if ($result_roles === false) {
        die("Lỗi truy vấn SQL: " . $conn->error);
    }
    $roles = [];
    if ($result_roles->num_rows > 0) {
        while ($row = $result_roles->fetch_assoc()) {
            $roles[] = $row;
        }
    }

    // Lấy danh sách các chức năng từ bảng chucnang
    $sql_chucnang = "SELECT * FROM chucnang WHERE TRANGTHAI = 1";
    $result_chucnang = $conn->query($sql_chucnang);
    if ($result_chucnang === false) {
        die("Lỗi truy vấn SQL: " . $conn->error);
    }
    $chucnang = [];
    if ($result_chucnang->num_rows > 0) {
        while ($row = $result_chucnang->fetch_assoc()) {
            $chucnang[] = $row;
        }
    }

    // Lấy danh sách các quyền đã được phân từ bảng phanquyen
    $sql_phanquyen = "SELECT * FROM phanquyen";
    $result_phanquyen = $conn->query($sql_phanquyen);
    if ($result_phanquyen === false) {
        die("Lỗi truy vấn SQL: " . $conn->error);
    }
    $phanquyen = [];
    if ($result_phanquyen->num_rows > 0) {
        while ($row = $result_phanquyen->fetch_assoc()) {
            $phanquyen[] = $row;
        }
    }

    function kiemTraQuyen($conn, $idQUYEN, $idCN, $thaotac) {
        $sql = "SELECT * FROM phanquyen WHERE idQUYEN = ? AND idCN = ? AND THAOTAC = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $idQUYEN, $idCN, $thaotac);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Đóng kết nối (nếu cần)
    $conn->close();
?>