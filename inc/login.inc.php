<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sdt = $_POST["sdt"];
    $pwd = $_POST["pwd"];

    try {

        require_once "../Model/db_connect.php";
        require_once "../Model/Client/user_model.php";
        require_once "../Controller/Client/login_contr.php";

        $conn = new Database();
        $conn->connect();

        $errors = [];

        is_input_empty($errors, $sdt, $pwd);

        $result = get_Phone($conn, $sdt);

        if (is_phone_wrong($result)) {
            $errors["phone_wrong"] = "Số điện thoại chưa đăng ký";
        }

        if (!is_phone_wrong($result) && is_pwd_wrong($pwd, $result["PASSWORD"]) ) {
            $errors["pwd_wrong"] = "Mật khẩu sai, vui lòng nhập lại";
        }

        require_once "config_session.inc.php";

        if ($errors) {
            $_SESSION["login_error"] = $errors;
            $login_data = [
                "phone" => $sdt
            ];

            $_SESSION["login_data"] = $login_data;
            header("Location: ../View/login-register/dangnhap.php");
            die();
        } else {
            $newSessionId = session_create_id();
            $sessionId = $newSessionId . "_" . $result["idTK"];
            session_id($sessionId);

            $_SESSION["username"] = htmlspecialchars($result["USERNAME"]);
            $_SESSION["sdt"] = htmlspecialchars($result["SDT"]);
            $_SESSION["email"] = htmlspecialchars($result["EMAIL"]);
            $_SESSION["hoten"] = htmlspecialchars($result["HOTEN"]);
            $_SESSION["id"] = htmlspecialchars($result["idTK"]);
            $_SESSION["sucess"] = "success";

            $conn = null;

            header("Location: ../View/trangchu/trangchu.php");

            die();
        }

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}