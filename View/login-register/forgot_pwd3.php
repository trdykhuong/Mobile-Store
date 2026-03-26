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
                <h2>Đổi mật khẩu</h2>
                <?php if (isset($_SESSION['message'])): ?>
                    <?php if ($_SESSION['message'] === "Đổi mật khẩu thành công! Bạn có thể đăng nhập ngay."): ?>
                        <div class="modal-mess">
                            <div class="message-form">
                                <div class="message-form-content">
                                    <?= $_SESSION['message'] ?>
                                    <button id="confirmButton">Xác nhận</button>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="message">
                            <?= $_SESSION['message'] ?>
                        </div>
                    <?php endif; ?>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>
                <form id="forgotPasswordForm" class="forgotPasswordForm" action="../../inc/reset_password.php" method="post">
                    <input type="password" name="new_password" placeholder="Nhập mật khẩu mới" required>
                    <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu mới" required>
                    <button type="submit">Đổi mật khẩu</button>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <?php include "../../View/trangchu/footer.php" ?>
    </footer>
</body>

<script>
    // Chuyển hướng đến trang đăng nhập khi nhấn nút xác nhận
    document.addEventListener("DOMContentLoaded", function() {
        const confirmButton = document.getElementById("confirmButton");
        if (confirmButton) {
            confirmButton.addEventListener("click", function() {
                window.location.href = "../../View/login-register/dangnhap.php";
            });
        }
    });
</script>