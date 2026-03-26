<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Khởi động session nếu chưa có
    }
?>

<link rel="stylesheet" href="../../css/login-register/dangky.css">

<body>
    <div class="home-page">
        <?php include('../../View/trangchu/trangchu.php') ?>
    </div>

    <div class="register-container" id="register-container">
        <div class="form-container-register">
            <div class="toggle-form-register">
                <div class="login-link">
                    <h1>Chào mừng trở lại</h1>
                    <p>Để giữ kết nối với chúng tôi, vui lòng đăng nhập bằng thông tin cá nhân của bạn</p>
                    <a href="dangnhap.php?page=login">Đăng nhập</a>
                </div>
            </div>
            <div class="register-content">
                <form action="../../inc/signup.inc.php" method="post" class="form-signup">
                    <div class="form-signup-left">
                        <h1>Đăng ký</h1>
                        <?php signup_inputs(); ?>
                        <button class="btn" type="submit">Đăng ký</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

<?php
function signup_inputs()
{
    //kiểm tra tên đăng nhập
    if (isset($_SESSION["signup_data"]) && isset($_SESSION["signup_error"])) {
        echo 
            '<div>
                <input type="text" class="input-btn" name="username" placeholder="Nhập tên đăng nhập"
                value="' . htmlspecialchars($_SESSION["signup_data"]["username"]) . '" />';
    } else {
        echo 
            '<div>
                <input type="text" class="input-btn" name="username" placeholder="Nhập tên đăng nhập"/>';
    }

    if (isset($_SESSION["signup_error"])) {
        // Kiểm tra lỗi tên đăng nhập
        $username_error = '';

        // Kiểm tra lỗi trống tên đăng nhập
        if (isset($_SESSION["signup_error"]["username_empty"])) {
            $username_error = $_SESSION["signup_error"]["username_empty"];
            unset($_SESSION["signup_error"]["username_empty"]);
        }
        // Kiểm tra lỗi trùng tên đăng nhập
        elseif (isset($_SESSION["signup_error"]["username_taken"])) {
            $username_error = $_SESSION["signup_error"]["username_taken"];
            unset($_SESSION["signup_error"]["username_taken"]);
        }

        echo (!empty($username_error) ? '<br><span class="span-mess">' . htmlspecialchars($username_error) . '</span>' : '') . '
        </div>
    ';
    }

    //kiểm tra số điện thoại
    if (isset($_SESSION["signup_data"]) && isset($_SESSION["signup_error"])) {

        echo '
        <div>
            <input type="text" class="input-btn" name="sdt" placeholder="Nhập số điện thoại"
            value="' . htmlspecialchars($_SESSION["signup_data"]["sdt"]) . '" />
            ';
    } else {
        echo '
        <div>
            <input type="text" class="input-btn" name="sdt" placeholder="Nhập số điện thoại"/> ';
    }

    if (isset($_SESSION["signup_error"])) {

        $sdt_error = '';

        // Kiểm tra lỗi trống số điện thoại
        if (isset($_SESSION["signup_error"]["sdt_empty"])) {
            $sdt_error = $_SESSION["signup_error"]["sdt_empty"];
            unset($_SESSION["signup_error"]["sdt_empty"]);
        }
        // Kiểm tra lỗi số điện thoại
        elseif (isset($_SESSION["signup_error"]["phone_taken"])) {
            $sdt_error = $_SESSION["signup_error"]["phone_taken"];
            unset($_SESSION["signup_error"]["phone_taken"]);
        }
        //kiểm tra lỗi số điện thoại ko hợp lệ
        elseif (isset($_SESSION["signup_error"]["phone_invalid"])) {
            $sdt_error = $_SESSION["signup_error"]["phone_invalid"];
            unset($_SESSION["signup_error"]["phone_invalid"]);
        }

        echo (!empty($sdt_error) ? '<br><span class="span-mess">' . htmlspecialchars($sdt_error) . '</span>' : '') . '
         </div>';
    }

    // Kiểm tra lỗi email
    if (isset($_SESSION["signup_data"]) && isset($_SESSION["signup_error"])) {

        echo '
        <div>
            <input type="email" class="input-btn" name="email" placeholder="Nhập email"
            value="' . htmlspecialchars($_SESSION["signup_data"]["email"]) . '" />
            ';
    } else {
        echo '
        <div>
            <input type="email" class="input-btn" name="email" placeholder="Nhập email"/>';
    }

    if (isset($_SESSION["signup_error"])) {
        $email_error = '';

        // Kiểm tra lỗi trống email
        if (isset($_SESSION["signup_error"]["email_empty"])) {
            $email_error = $_SESSION["signup_error"]["email_empty"];
            unset($_SESSION["signup_error"]["email_empty"]);
        }
        // Kiểm tra lỗi trùng email
        elseif (isset($_SESSION["signup_error"]["email_taken"])) {
            $email_error = $_SESSION["signup_error"]["email_taken"];
            unset($_SESSION["signup_error"]["email_taken"]);
        }
        // Kiểm tra lỗi email không hợp lệ
        elseif (isset($_SESSION["signup_error"]["email_invalid"])) {
            $email_error = $_SESSION["signup_error"]["email_invalid"];
            unset($_SESSION["signup_error"]["email_invalid"]);
        }

        echo (!empty($email_error) ? '<br><span class="span-mess">' . htmlspecialchars($email_error) . '</span>' : '') . '
            <br>
            <small>
                Hóa đơn VAT khi mua hàng sẽ được gửi qua email này
            </small>
        </div>';

    }

    // Kiểm tra lỗi họ và tên
    if (isset($_SESSION["signup_data"]) && isset($_SESSION["signup_error"])) {
        echo '
            <div>
                <input type="text" class="input-btn" name="hoten" placeholder="Nhập họ và tên" 
                value="' . htmlspecialchars($_SESSION["signup_data"]["hoten"]) . '" />
        ';
    } else {
        echo '
            <div>
                <input type="text" class="input-btn" name="hoten" placeholder="Nhập họ và tên" />
        ';
    }

    if (isset($_SESSION["signup_error"])) {
        $hoten_error = '';
        if (isset($_SESSION["signup_error"]["hoten_empty"])) {
            $hoten_error = $_SESSION["signup_error"]["hoten_empty"];
            unset($_SESSION["signup_error"]["hoten_empty"]);
        }

        echo (!empty($hoten_error) ? '<br><span class="span-mess">' . htmlspecialchars($hoten_error) . '</span>' : '') . '
            </div>';
    }

    // Kiểm tra mật khẩu
    echo '
    <div>
        <input name="pass1" class="input-btn" placeholder="Nhập mật khẩu" type="password" />';

    if (isset($_SESSION["signup_error"])) {
        $pass1_error = '';

        // Kiểm tra lỗi trống mật khẩu
        if (isset($_SESSION["signup_error"]["pwd_empty"])) {
            $pass1_error = $_SESSION["signup_error"]["pwd_empty"];
            unset($_SESSION["signup_error"]["pwd_empty"]);
        }
        // Kiểm tra lỗi mật khẩu không hợp lệ
        elseif (isset($_SESSION["signup_error"]["pwd_invalid"])) {
            $pass1_error = $_SESSION["signup_error"]["pwd_invalid"];
            unset($_SESSION["signup_error"]["pwd_invalid"]);
        }

        echo (!empty($pass1_error) ? '<br><span class="span-mess">' . htmlspecialchars($pass1_error) . '</span>' : '') . '
            <br>
            <small>
                (*) Mật khẩu tối thiểu 6 ký tự, có ít nhất 1 chữ và 1 số. (VD: 12345a)
            </small>
        </div>';
    }

    // Kiểm tra lỗi xác nhận mật khẩu
    echo '
    <div>
        <input name="pass2" class="input-btn" placeholder="Nhập lại mật khẩu" type="password"/> ';

    if (isset($_SESSION["signup_error"])) {
        $pass2_error = '';

        // Kiểm tra lỗi mật khẩu xác nhận không khớp
        if (isset($_SESSION["signup_error"]["check_loop"])) {
            $pass2_error = $_SESSION["signup_error"]["check_loop"];
            unset($_SESSION["signup_error"]["check_loop"]);
        }

        echo (!empty($pass2_error) ? '<br><span class="span-mess">' . htmlspecialchars($pass2_error) . '</span>' : '') . '
        </div>';
    }

    // Kiểm tra lỗi checkbox đồng ý điều khoản
    if(isset($_SESSION["signup_data"]) && isset($_SESSION["signup_error"])) {
        $checkDK_value = $_SESSION["signup_data"]["check_DK"] ? 'checked' : '';
        echo '
        <div class="check-box-register">
            <label>
                <input name="checkDK" type="checkbox"' .  $checkDK_value . ' style="width: 12px;"/>
                <p>
                    Tôi đồng ý với các
                    <a href="#">điều khoản sử dụng và chính sách bảo mật.</a>
                </p>
            </label>';
    } else {
        echo '
        <div>
            <label>
                <input name="checkDK" type="checkbox"/>
                <p>
                    Tôi đồng ý với các
                    <a href="#">điều khoản sử dụng và chính sách bảo mật.</a>
                </p>
            </label>';
    }

    if (isset($_SESSION["signup_error"])) {
        $checkDK_error = '';
        if (isset($_SESSION["signup_error"]["check_DK"])) {
            $checkDK_error = $_SESSION["signup_error"]["check_DK"];
            unset($_SESSION["signup_error"]["check_DK"]);
        }

        echo (!empty($checkDK_error) ? '<span class="span-mess">' . htmlspecialchars($checkDK_error) . '</span>' : '') . '
            </div>';
    }
}
?>

<script>
    // Đóng modal đăng nhập khi click ra ngoài
    window.addEventListener("click", function(event) {
        let modal = document.getElementById("register-container");
        let modalContent = document.querySelector(".form-container-register");
        if (event.target === modal && !modalContent.contains(event.target)) {
            window.location.href = "../../View/trangchu/trangchu.php"; // Chuyển hướng về trang chủ
        }
    });
</script>