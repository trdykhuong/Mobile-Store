<?php
$conn = mysqli_connect("localhost:3306", "root", "", "chdidong");
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Lấy danh sách nhân viên có ngày nhận chức trùng với hôm nay
$today = date("Y-m-d");
$sql = "SELECT idTK, vitrimoi FROM lsuthangchuc WHERE ngaynhamchuc = '$today' AND trangthai = 0";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $idTK = $row['idTK'];
    $newRole = $row['vitrimoi'];

    // Cập nhật chức vụ trong bảng tài khoản
    $updateRoleQuery = "UPDATE taikhoan SET idQUYEN = '$newRole' WHERE idTK = '$idTK'";
    mysqli_query($conn, $updateRoleQuery);

    // Đánh dấu trạng thái đã cập nhật (trangthai = 1)
    $updateStatusQuery = "UPDATE lsuthangchuc SET trangthai = 1 WHERE idTK = '$idTK' AND ngaynhamchuc = '$today'";
    mysqli_query($conn, $updateStatusQuery);
}

mysqli_close($conn);
echo "Cập nhật chức vụ hoàn tất!";
?>
