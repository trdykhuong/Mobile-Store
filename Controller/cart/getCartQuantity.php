<?php
    session_start();
    include("../../Model/db_connect.php");

    // Khởi tạo đối tượng Database và kết nối
    $db = new Database();
    $conn = $db->connect(); // Lấy kết nối cơ sở dữ liệu

    // Kiểm tra kết nối cơ sở dữ liệu
    if (!$conn) {
        die("Kết nối cơ sở dữ liệu thất bại.");
    }

    $user = isset($_SESSION['username']) ? $_SESSION['username'] : NULL;

    if ($user) {
        $query = "SELECT COUNT(idCT) AS quantity FROM chitietgiohang ct 
                JOIN taikhoan tk ON ct.idTK = tk.idTK 
                WHERE tk.USERNAME = '$user'";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Lỗi truy vấn: " . mysqli_error($conn));
        }

        $row = mysqli_fetch_assoc($result);
        echo $row['quantity'];
    } else {
        echo 0; 
    }
?>