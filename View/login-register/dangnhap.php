<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Khởi động session nếu chưa có
    }

    if(isset($_SESSION['signup']) && $_SESSION['signup'] == "success")  {
        echo "
        <div class='success-message' id='success-message'>
            Đăng ký thành công vui lòng đăng nhập lại!
        </div>
        ";
        unset($_SESSION['signup']);
    }
?>

<link rel="stylesheet" href="../../css/login-register/dangnhap.css">

<body>
    <div class="home-page">
        <?php include('../../View/trangchu/trangchu.php') ?>
    </div>

    <div class="login-container" id="login-container">
        <div class="form-container-login">
            <div class="toggle-form">
                <div class="register-link">
                    <h1>Xin chào bạn</h1>
                    <p>Nhập thông tin cá nhân của bạn và bắt đầu hành trình cùng chúng tôi</p>
                    <a href="dangky.php?page=signup">Đăng ký</a>
                </div>
            </div>
            <div class="login-content">
                <form action="../../inc/login.inc.php" method="post" class="form-login">
                    <h1>Đăng nhập</h1>
                    <?php login_inputs() ?>
                    <button class="btn">Đăng nhập</button>
                </form>
            </div>
        </div>
    </div>
</body>

<?php
function login_inputs()
{
    if (isset($_SESSION["login_data"]) && isset($_SESSION["login_error"])) {
        echo 
        '<div>
            <input require class="input-btn" name="sdt" placeholder="Nhập số điện thoại" type="text" value="' . $_SESSION["login_data"]["phone"] . '" >';
    } else {
        echo 
        '<div>
            <input require class="input-btn" name="sdt" placeholder="Nhập số điện thoại" type="text" />';
    }

    if (isset($_SESSION["login_error"])) {

        // Kiểm tra lỗi số điện thoại
        $sdt_error = '';

        // Kiểm tra lỗi trống số điện thoại
        if (isset($_SESSION["login_error"]["sdt_empty"])) {
            $sdt_error = $_SESSION["login_error"]["sdt_empty"];
            unset($_SESSION["login_error"]["sdt_empty"]);
        }
        // Kiểm tra lỗi số điện thoại không hợp lệ
        elseif (isset($_SESSION["login_error"]["phone_wrong"])) {
            $sdt_error = $_SESSION["login_error"]["phone_wrong"];
            unset($_SESSION["login_error"]["phone_wrong"]);
        }

        echo (!empty($sdt_error) ? '<br><span class="span-mess">' . htmlspecialchars($sdt_error) . '</span>' : '') . '
            </div>';
    }
    echo '
        <div>
            <input require class="input-btn" name="pwd" placeholder="Nhập mật khẩu" type="password" />
    ';
    if (isset($_SESSION['login_error'])) {

        // Kiểm tra lỗi mật khẩu
        $pass1_error = '';

        // Kiểm tra lỗi trống mật khẩu
        if (isset($_SESSION["login_error"]["pwd_empty"])) {
            $pass1_error = $_SESSION["login_error"]["pwd_empty"];
            unset($_SESSION["login_error"]["pwd_empty"]);
        }
        // Kiểm tra lỗi mật khẩu sai
        elseif (isset($_SESSION["login_error"]["pwd_wrong"])) {
            $pass1_error = $_SESSION["login_error"]["pwd_wrong"];
            unset($_SESSION["login_error"]["pwd_wrong"]);
        }

        echo (!empty($pass1_error) ? '<br><span class="span-mess">' . htmlspecialchars($pass1_error) . '</span>' : '');

    }
    echo ' 
            <br>
            <a href="../../View/login-register/forgot_pwd.php" class="forget">Quên mật khẩu?</a>
        </div>';
}
?>

<script>
    // Đóng modal đăng nhập khi click ra ngoài
    window.addEventListener("click", function(event) {
        let modal = document.getElementById("login-container");
        let modalContent = document.querySelector(".form-container-login");
        if (event.target === modal && !modalContent.contains(event.target)) {
            window.location.href = "../../View/trangchu/trangchu.php"; // Chuyển hướng về trang chủ
        }
    });

    // Hiển thị thông báo đăng nhập thành công
    document.addEventListener("DOMContentLoaded", function() {
        const successMessage = document.getElementById("success-message");
        if (successMessage) {
            setTimeout(function() {
                successMessage.style.display = "none";
            }, 3000);
        }
    });
</script>