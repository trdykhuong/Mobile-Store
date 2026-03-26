<?php
    session_start();
    $conn=mysqli_connect("localhost:3306", "root", "", "chdidong");

    header('Content-Type: application/json');

    if (!isset($_SESSION['idNV'])) {
        echo json_encode(['error' => 'Bạn chưa đăng nhập']);
        exit;
    }

    $idNV = $_SESSION['idNV'];

    $sql = 'SELECT tk.HOTEN, nv.IMG, q.TENQUYEN 
            FROM taikhoan tk 
            JOIN nhanvien nv ON tk.idTK = nv.idTK
            JOIN quyen q ON tk.idQUYEN = q.idQUYEN 
            WHERE tk.idTK = ?';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $idNV);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $info = [
            'hoten' => $user['HOTEN'],
            'img' => $user['IMG'],
            'quyen' => $user['TENQUYEN']
        ];
        echo json_encode($info);
    } else {
        echo json_encode(['error' => 'Không tìm thấy thông tin người dùng']);
    }

    $stmt->close(); 
    $conn->close(); 
?>