<?php
require '../../vendor/autoload.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = mysqli_connect("localhost:3306", "root", "", "chdidong");
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

if (isset($_POST["addEmployee"])) {
    if (
        !empty($_POST["hoten"])
        && !empty($_FILES["txtImg"]["name"])
        && !empty($_POST["date"])
        && !empty($_POST["gender"])
        && !empty($_POST["address"])
        && !empty($_POST["phone"])
        && !empty($_POST["email"])
        && !empty($_POST["ngaylam"])
        && isset($_POST["QUYEN"])
    ) {
        $hoten = $conn->real_escape_string($_POST["hoten"]);
        $ngaysinh = $conn->real_escape_string($_POST["date"]);
        $gioitinh = $conn->real_escape_string($_POST["gender"]);
        $diachi = $conn->real_escape_string($_POST["address"]);
        $sdt = $conn->real_escape_string($_POST["phone"]);
        $email = $conn->real_escape_string($_POST["email"]);
        $ngaylam = $conn->real_escape_string($_POST["ngaylam"]);
        $quyen = intval($_POST["QUYEN"]);
        $img_temp = $_FILES["txtImg"]["tmp_name"];
        $images_dir = $_SERVER["DOCUMENT_ROOT"] . "/images/employee";

        $options = ["cost" => 12];

        $hashedPwd = password_hash($ngaysinh, PASSWORD_BCRYPT, $options);

        // Kiểm tra số điện thoại tồn tại
        $stmt = $conn->prepare("SELECT * FROM taikhoan WHERE SDT = ? OR EMAIL = ?");
        $stmt->bind_param("ss", $sdt, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Số điện thoại hoặc email đã tồn tại trong hệ thống. Vui lòng kiểm tra lại!')</script>";
        } else {
            // Thêm tài khoản
            // Trạng thái 2 là trạng thái quản lý nhân sự mới tạo tài khoản cho nhân viên
            $insert_taikhoan = mysqli_query($conn, "INSERT INTO taikhoan (USERNAME, PASSWORD, SDT, EMAIL, HOTEN, idQUYEN, TRANGTHAI) 
                                                    VALUES('$sdt', '$hashedPwd', '$sdt', '$email', '$hoten', '$quyen', 2)");

            if (!$insert_taikhoan) {
                die("Lỗi thêm tài khoản: " . mysqli_error($conn));
            }

            // Lấy ID tài khoản vừa tạo
            $idTK = mysqli_insert_id($conn);

            // Tạo username mới
            $newUsername = "NV" . $idTK;
            // Cập nhật username cho tài khoản vừa tạo
            $sql_update = "UPDATE taikhoan SET USERNAME = '$newUsername' WHERE idTK = $idTK";
            if (!mysqli_query($conn, $sql_update)) {
                echo "<script>alert('Lỗi blabla')</script>";
            }

            // Đặt tên file ảnh
            $img_extension = pathinfo($_FILES["txtImg"]["name"], PATHINFO_EXTENSION);
            $new_name = 'NV' . $idTK . '.' . $img_extension;
            $img_path = "$images_dir/$new_name";

            if (!move_uploaded_file($img_temp, $img_path)) {
                $error = error_get_last();
                die("Lỗi khi upload ảnh: " . $error['message']);
            }
            echo "<script>console.log($img_path)</script>";
            // Thêm nhân viên
            $insert_nhanvien = mysqli_query($conn, "INSERT INTO nhanvien (idTK, GIOITINH, NGAYSINH, DIACHI, IMG, NGAYVAOLAM, TINHTRANG) 
                                                    VALUES('$idTK', '$gioitinh', '$ngaysinh', '$diachi', '$new_name', '$ngaylam', 'Dang lam')");

            if (!$insert_nhanvien) {
                die("Lỗi thêm nhân viên: " . mysqli_error($conn));
            } else {
                // echo "<script>alert('Thêm nhân viên thành công!')</script>";
                echo "<script>window.location.href = '?page=employee';</script>";
            }

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
                $mail->Subject = 'Thông tin tài khoản của nhân viên';
                $mail->Body = "Đây là thông tin tài khoản của bạn. 
                Vui lòng không cung cấp thông tin cho người khác. 
                Xin cảm ơn người đẹp.
                Vui lòng đăng nhập hệ thống theo thông tin dưới đây
                username: $newUsername
                mật khẩu: $ngaysinh";

                $mail->send();

                exit();
            } catch (Exception $e) {
                echo "Lỗi gửi email: " . $mail->ErrorInfo;
            }

        }
    }
}
?>