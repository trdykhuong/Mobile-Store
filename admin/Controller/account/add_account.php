<?php
function getAllRoles() {
    $connect = new mysqli("localhost:3306", "root", "", "chdidong");
    $dsQuyen = [];

    if ($connect->connect_error) {
        die("Kết nối thất bại: " . $connect->connect_error);
    }

    $sql = "SELECT idQUYEN, TENQUYEN FROM quyen";
    $result = $connect->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Bỏ quyền idQUYEN = 1 (Admin) ra khỏi danh sách
            if ($row['idQUYEN'] != 1) {
                $dsQuyen[] = $row;
            }
        }
    }

    $connect->close();
    return $dsQuyen;
}

// Hàm xử lý thêm tài khoản
function addAccount() {
    $connect = new mysqli("localhost:3306", "root", "", "chdidong");

    if ($connect->connect_error) {
        die("Kết nối thất bại: " . $connect->connect_error);
    }

    $username = $connect->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $idQUYEN = (int)$_POST['idQUYEN'];
    $trangthai = (int)$_POST['trangthai'];

    // $stmt = $connect->prepare("INSERT INTO taikhoan (USERNAME, HOTEN, EMAIL, PASSWORD, idQUYEN, TRANGTHAI) 
    //                           VALUES (?, ?, ?, ?, ?, ?)");

    // Đúng tên cột
    $stmt = $connect->prepare("INSERT INTO taikhoan (USERNAME, PASSWORD, idQUYEN, TRANGTHAI) 
                              VALUES (?, ?, ?, ?)");

    if (!$stmt) {
        die("Lỗi prepare: " . $connect->error);
    }

    $stmt->bind_param("ssii", $username, $password, $idQUYEN, $trangthai);

    if ($stmt->execute()) {
        echo "<script>alert('Thêm tài khoản thành công!'); 
        console.log('231');
        window.location.href = '../../View/account/account.php'</script>";
    } else {
        echo "<script>alert('Thêm tài khoản thất bại: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $connect->close();
}
