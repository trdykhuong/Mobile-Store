<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $hoten = $_POST["hoten"];
    $sdt = $_POST["sdt"];
    $email = $_POST["email"];
    $pwd = $_POST["pass1"];
    $pwd2 = $_POST["pass2"];
    $checkDk = $_POST["checkDK"];

    try {
        require_once "../Model/db_connect.php";
        require_once "../Controller/Client/sign_contr.php";
        require_once "../Model/Client/user_model.php";

        $conn = new Database();
        $conn->connect();

        $errors = [];

        is_input_empty($errors, $username, $pwd, $hoten, $sdt, $email);

        $resultU = getUsername($username, $conn);
        if (is_taken($resultU)) {
            $errors["username_taken"] = "Tên người dùng đã tồn tại";
        }

        $resultP = get_Phone($conn, $sdt);

        if (is_taken($resultP)) {
            $errors["phone_taken"] = "Số điện thoại đã tồn tại";
        }

        $resultE = get_Email($conn, $email);
        if (is_taken($resultE)) {
            $errors["email_taken"] = "Email đã tồn tại";
        }

        if (is_email_invalid($email)) {
            $errors["email_invalid"] = "Email không hợp lệ";
        }

        if (is_phone_invalid($sdt)) {
            $errors["phone_invalid"] = "Số điện thoại không hợp lệ";
        }

        if (is_pwd_invalid($pwd)) {
            $errors["pwd_invalid"] = "Mật khẩu không hợp lệ";
        }

        if (is_check_pwd($pwd, $pwd2)) {
            $errors["check_loop"] = "Mật khẩu không khớp, vui lòng nhập lại";
        }

        if(!isset($checkDk)) {
            $errors["check_DK"] = "Cần chọn giá trị";
        }

        require_once "config_session.inc.php";

        if ($errors) {
            $_SESSION["signup_error"] = $errors;

            $signupData = [
                "username" => $username,
                "email" => $email,
                "sdt" => $sdt,
                "hoten" => $hoten,
                "check_DK" => $checkDk
            ];

            $_SESSION["signup_data"] = $signupData;
        
            header("Location: ../../View/login-register/dangky.php");
            // echo "<script>window.history.back();</script>";
            // print_r($_SESSION["signup_error"]);
            
           exit();
        } else {
            create_taikhoan($conn, $username, $email, $sdt, $hoten, $pwd);
            $_SESSION["signup"] = "success";
            $conn = null;

            header("Location: ../View/login-register/dangnhap.php");

        }

        die();

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../../View/trangchu/trangchu.php");
    die();
}