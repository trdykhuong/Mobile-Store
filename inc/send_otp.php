<?php
// Nạp autoload của Composer
require '../vendor/autoload.php'; // Đảm bảo đường dẫn đúng

// Import các lớp PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'config_session.inc.php';
require '../Model/Client/user_model.php';
require '../Model/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);

    $conn = new Database();
    $conn->connect();

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // echo "<script>alert('Email không hợp lệ!');</script>";
        $_SESSION['message'] = "Email không hợp lệ!";
        header("Location: ../../View/login-register/forgot_pwd.php");
        exit;
    }

    $result = get_Email($conn, $email);
    if (!$result) {
        $_SESSION["email_taken"] = "not in";
        header("Location: ../../View/login-register/forgot_pwd.php");
        exit();
    } 

    // Tạo mã OTP 6 chữ số
    $otp = rand(100000, 999999);

    // Lưu vào session
    $_SESSION["otp"] = $otp;
    $_SESSION["otp_email"] = $email;
    $_SESSION["otp_expire"] = time() + 300; // Hết hạn sau 5 phút

    // Cấu hình gửi email
    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = "UTF-8"; // Đặt mã hóa UTF-8 để hỗ trợ tiếng Việt
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Thay bằng SMTP của bạn
        $mail->SMTPAuth = true;
        $mail->Username = 'ikon1605@gmail.com'; // Email của bạn
        $mail->Password = 'wuzs ogxs auif bcns'; // Mật khẩu ứng dụng
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Gửi email
        $mail->setFrom('ikon1605@gmail.com', 'Your Website');
        $mail->addAddress($email);
        $mail->Subject = 'Mã OTP của bạn';
        $mail->Body = "Mã OTP của bạn là: $otp. Mã có hiệu lực trong 5 phút.";

        $mail->send();
        $_SESSION["send_email"] = "success";
        header("Location: ../../View/login-register/forgot_pwd2.php");
        
        exit();
    } catch (Exception $e) {
        echo "Lỗi gửi email: " . $mail->ErrorInfo;
    }
}
?>