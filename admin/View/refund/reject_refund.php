<?php
    include "../checkLogin.php";
    
    $connect = new mysqli("localhost", "root", "", "chdidong");
    if ($connect->connect_error) {
        die("Kết nối thất bại: " . $connect->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id = intval($_POST['id']);

        $stmt = $connect->prepare("UPDATE hoantien SET trangthai = 2 WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Yêu cầu hoàn tiền đã bị từ chối."]);
        } else {
            echo json_encode(["success" => false, "message" => "Có lỗi xảy ra. Vui lòng thử lại."]);
        }
        $stmt->close();
        exit();
    }
?>