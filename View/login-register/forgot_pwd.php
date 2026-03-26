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
                    <a href="../../View/login-register/dangnhap.php">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>
                <h2>Đặt lại mẩt khẩu</h2>
                <?php if (isset($_SESSION['message']) || (isset($_SESSION["email_taken"]) && $_SESSION["email_taken"] === "not in")): ?>
                    <div class="message">
                        <?= $_SESSION['message'] ?? 'Email chưa tồn tại trong hệ thống' ?>
                        <?php unset($_SESSION['message']); ?>
                        <?php unset($_SESSION["email_taken"]); ?>
                    </div>
                <?php endif; ?>
                <form id="forgotPasswordForm" class="forgotPasswordForm" action="../../inc/send_otp.php" method="post">
                    <input type="email" id="email" name="email" placeholder="Nhập email" required>
                    <button type="submit">Tiếp theo</button>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <?php include "../../View/trangchu/footer.php" ?>
    </footer>
</body>