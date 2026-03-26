<?php
    session_start();

    $connect = new mysqli("localhost", "root", "", "chdidong");

    if ($connect->connect_error) {
        echo json_encode(array("status" => false, "message" => "Kết nối thất bại: " . $connect->connect_error));
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $connect->real_escape_string($_POST['username']);
        $password = $_POST['password'];

        $query = "SELECT * FROM taikhoan WHERE USERNAME = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        header('Content-Type: application/json');

    if ($user && password_verify($password, $user['PASSWORD'])) {
        if ($user['TRANGTHAI'] == 0) {
            // $alert = "Tài khoản đang bị khóa!";
            $respone = [
                'status' => 0,
                'message' => "Tài khoản đang bị khóa"
            ];
            // header("Location: ../View/changPass.php");
            echo json_encode($respone);

            exit();
        }else if($user['TRANGTHAI'] == 2) {
     
            // $_SESSION['idNV_2'] = isset($user['idTK']);
            $_SESSION['idNV'] = $user['idTK'];
            // $idtemp = $_SESSION['idNV'];
            // echo '<script>alert("' .$idtemp.'")</script>';
            $respone = [
                'status' => 2,
                'message' => "123cmm"
            ];
            // header("Location: ../View/changPass.php");
            echo json_encode($respone);

            exit();

        } else {
            // Lưu thông tin người dùng và quyền vào session
            $_SESSION['user'] = $username;
            $_SESSION['role'] = $user['idQUYEN'];
            $_SESSION['idNV'] = $user['idTK'];

            // $idtemp = $_SESSION['idNV'];
            // echo '<script>alert("' .$idtemp.'")</script>';

                // Lấy các chức năng người dùng có quyền "VIEW"
                $permissions = [];
                $query = "SELECT c.TENCN FROM phanquyen p 
                        JOIN chucnang c ON p.idCN = c.idCN 
                        WHERE p.idQUYEN = ? AND p.THAOTAC = 'XEM'";
                $stmt = $connect->prepare($query);
                $stmt->bind_param("i", $user['idQUYEN']);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $permissions[] = $row['TENCN'];
                }
                $_SESSION['permissions'] = $permissions;

                echo json_encode(array("status" => 1, "message" => "Đăng nhập thành công!"));
            }
        } else {
            echo json_encode(array("status" => 0, "message" => "Sai tên đăng nhập hoặc mật khẩu!"));
        }
        exit();
    }
?>

<title>Đăng nhập hệ thống</title>
<link rel="stylesheet" href="../../css/admin/login.css">

<body>
    <div class="login-box">
        <h2>Đăng nhập hệ thống</h2>
        <form method="POST" id="login-form">
            <div class="user-box">
                <input type="text" name="username" required>
                <label for="">Username</label>
            </div>

            <div class="user-box">
                <input type="password" name="password" required>
                <label for="">Password</label>
            </div>
            <!-- <p class="error"></p> -->
            <button type="submit">Đăng nhập</button>
        </form>
    </div>

    <div class="moving-div">
        <img src="../../images/system/error copy.png" alt="">
        <p class="error"></p>
    </div>
</body>
<script src="../../js/admin/loginAdmin.js"></script>