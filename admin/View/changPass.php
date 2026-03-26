<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kết nối cơ sở dữ liệu
$connect = new mysqli("localhost", "root", "", "chdidong");

if ($connect->connect_error) {
    die("Kết nối thất bại: " . $connect->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updatePassword'])) {
    if (isset($_SESSION['idNV'])) {
        echo "<script>console.log('Vô đây rrr)</script>";
        $idTK = $_SESSION['idNV'];
        $password = $_POST['password'];
        $rspassword = $_POST['rspassword'];

        // Kiểm tra mật khẩu có khớp không
        if ($password !== $rspassword) {
            echo "Mật khẩu không khớp";
            exit;
        }

        $options = ["cost" => 12];
        $hashed_password = password_hash($password, PASSWORD_BCRYPT, $options);

        $sql = "UPDATE taikhoan SET PASSWORD = ?, TRANGTHAI = 1 WHERE idTK = ?";

        // Chuẩn bị và thực thi truy vấn
        if ($stmt = $connect->prepare($sql)) {
            $stmt->bind_param("si", $hashed_password, $idTK);
            if ($stmt->execute()) {
                echo "cập nhật thành công";
                header("Location: ../View/loginAdmin.php");
            } else {
                return "Lỗi khi cập nhật: " . $stmt->error;
            }
        }
    } else {
        echo "Không có idTK";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Đổi mật khẩu</title>
    <link rel="stylesheet" href="../../css/admin/login.css">
</head>

<body>
    <h2>Đổi mật khẩu nhân viên</h2>
    <form method="POST">
        <input type="password" name="password" placeholder="Mật khẩu mới" required autocomplete="new-password">
        <input type="password" name="rspassword" placeholder="Nhập lại mật khẩu" required autocomplete="new-password">
        <button type="submit" name="updatePassword">Cập nhật mật khẩu</button>
    </form>
</body>

</html>
