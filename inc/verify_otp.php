<?php
require 'config_session.inc.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["otp"])) {
    $user_otp = trim($_POST["otp"]);

    if (!isset($_SESSION["otp"]) || !isset($_SESSION["otp_expire"])) {
        // echo "Không có mã OTP nào được gửi!";
        $_SESSION['message'] = "Không có mã OTP nào được gửi!";
        header("Location: ../../View/login-register/forgot_pwd.php");
        exit;
    }

    if (time() > $_SESSION["otp_expire"]) {
        // echo "Mã OTP đã hết hạn!";
        // unset($_SESSION["otp"]);
        // unset($_SESSION["otp_expire"]);
        $_SESSION['message'] = "Mã OTP đã hết hạn!";
        unset($_SESSION["otp"]);
        unset($_SESSION["otp_expire"]);
        header("Location: ../../View/login-register/forgot_pwd2.php");
        exit;
    }

    if ($user_otp == $_SESSION["otp"]) {
        echo "Xác minh thành công!";
        unset($_SESSION["otp"]);
        unset($_SESSION["otp_expire"]);
        // Chuyển hướng đến trang đổi mật khẩu
        header("Location: ../../View/login-register/forgot_pwd3.php");
        exit;
    } else {
        // echo "Mã OTP không đúng!";
        $_SESSION['message'] = "Mã OTP không đúng!";
        header("Location: ../../View/login-register/forgot_pwd2.php");
    }
}
?>