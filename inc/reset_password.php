<?php
require 'config_session.inc.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION["otp_email"])) {
        // echo "Bạn chưa xác minh OTP!";
        $_SESSION['message'] = "Bạn chưa xác minh OTP!";
        header("Location: ../../View/login-register/forgot_pwd2.php");
        exit;
    }

    $email = $_SESSION["otp_email"];
    $new_password = trim($_POST["new_password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    try {
        require_once "../Model/db_connect.php";
        require_once "../Model/Client/user_model.php";

        $conn = new Database();
        $conn->connect();

        // Kiểm tra mật khẩu có khớp không
        if ($new_password !== $confirm_password) {
            // echo "Mật khẩu xác nhận không khớp!";
            // exit;
            $_SESSION['message'] = "Mật khẩu xác nhận không khớp!";
            header("Location: ../../View/login-register/forgot_pwd3.php");
            exit;
        }
        // echo $email;
        $result = get_Email($conn, $email);
        if($result) {
            if (updatePwd($conn, $new_password, $result["idTK"])) {
                // echo "<script>alert('Đổi mật khẩu thành công! Bạn có thể đăng nhập ngay.');</script>";
                // unset($_SESSION["otp_email"]); // Xóa session sau khi đổi mật khẩu
                // echo "<script>window.location.href = '../View/trangchu/dangnhap.php';</script>";
                // exit;
                $_SESSION['message'] = "Đổi mật khẩu thành công! Bạn có thể đăng nhập ngay.";
                unset($_SESSION["otp_email"]); // Xóa session sau khi đổi mật khẩu
                header("Location: ../../View/login-register/forgot_pwd3.php");
                exit;
            }
        } else {
            // echo "Lỗi đổi mật khẩu. Vui lòng thử lại!";
            $_SESSION['message'] = "Lỗi đổi mật khẩu. Vui lòng thử lại!";
            header("Location: ../../View/login-register/forgot_pwd3.php");
            exit;
        }
        
        $conn = null;
        // header("Location: ../View/trangchu/dangnhap.php");

        die();

    } catch (Exception $e) {
        die("query failed: " . $e->getMessage());
    }
}