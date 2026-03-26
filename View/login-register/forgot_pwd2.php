<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Khởi động session nếu chưa có
}
?>

<link rel="stylesheet" href="../../css/login-register/forgot_pwd.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<body>
    <header>
        <div class="head">
            <div class="head-logo">
                <a href="../../View/trangchu/trangchu.php">
                    <img src="../../images/system/logo copy.png" alt="">
                </a>
                <h2>Đặt lại mật khẩu</h2>
            </div>
        </div>
    </header>

    <main class="main" id="main">
        <div class="container">
            <div class="forgot-pwd">
                <div class="return-page">
                    <a href="../../View/login-register/forgot_pwd.php">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>
                <h2>Nhập mã OTP</h2>
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="message">
                        <?= $_SESSION['message'] ?>
                        <?php unset($_SESSION['message']); ?>
                    </div>
                <?php endif; ?>
                <form id="forgotPasswordForm" class="forgotPasswordForm" action="../../inc/verify_otp.php" method="post">
                    <input type="text" name="otp" placeholder="Nhập OTP" required>
                    <button type="submit">Xác minh</button>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <?php include "../../View/trangchu/footer.php" ?>
    </footer>

    <?php  if(isset($_SESSION["send_email"]) && $_SESSION["send_email"] === "success"): ?>
        <div id="success-message" class="success-message">
            OTP đã được gửi! Vui lòng kiểm tra lại email của bạn.
        </div>
        <?php unset($_SESSION["send_email"]); ?>
    <?php endif; ?>
</body>

<script>
    // Hiển thị thông báo 
    document.addEventListener("DOMContentLoaded", function() {
        const successMessage = document.getElementById("success-message");
        if (successMessage) {
            setTimeout(function() {
                successMessage.style.display = "none";
            }, 3000);
        }
    });
</script>