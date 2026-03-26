<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = mysqli_connect("localhost:3306", "root", "", "chdidong");
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Xử lý cập nhật dữ liệu
if (isset($_POST['updateEmployee'])) {

    $idTK = $_POST['idTK'];
    $hoten = htmlspecialchars($_POST['hoten']);
    $date = $_POST['date'];
    $gender = $_POST['gender'];
    $address = htmlspecialchars($_POST['address']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $quyen = isset($_POST['vtrilviec']) ? $_POST['vtrilviec'] : 1;
    $quyenmoi = isset($_POST['QUYEN']) ? $_POST['QUYEN'] : 1;
    $ngayNhanChuc = $_POST['ngay_nhan_chuc'] ?? NULL;

    echo "<script>console.log('$quyen', ' $quyenmoi')</script>";

    $img = basename($_FILES["Img"]["name"]); //File ảnh mới
    $img_old = $_POST['img-name']; //Tên file ảnh cũ

    $images_dir = "../../images/employee/";  //đường dẫn file ảnh

    $result = mysqli_query($conn, "SELECT q.LUONGCB FROM nhanvien nv JOIN taikhoan tk ON nv.idTK=tk.idTK JOIN quyen q ON tk.idQUYEN = q.idQUYEN WHERE tk.idTK = '$idTK'");
    $row = mysqli_fetch_assoc($result);
    $luongcu = $row['LUONGCB'];

    // Lấy lương mới từ bảng quyen theo vị trí mới
    $result_new_salary = mysqli_query($conn, "SELECT LUONGCB FROM quyen WHERE idQUYEN = '$quyenmoi'");
    $row_new_salary = mysqli_fetch_assoc($result_new_salary);
    $luongmoi = $row_new_salary['LUONGCB']; //Lấy lương chức vụ khác


    //Lấy đuôi file mới
    $ext_img = pathinfo($img, PATHINFO_EXTENSION);
    $newNameIMG = $img_old;

    if (strlen($ext_img) > 0) {
        //Xóa file ảnh cũ
        if (!empty($img_old) && file_exists($images_dir . $img_old)) {
            unlink($images_dir . $img_old);
        }

        $url_new = 'NV' . $idTK . '.' . $ext_img;
        $img_path = $images_dir . $url_new;
        echo "<script>console.log(" . json_encode($img_path) . ");</script>";

        if (!move_uploaded_file($_FILES["Img"]["tmp_name"], $img_path)) {
            $error = error_get_last();
            echo "<script>console.log('Lỗi khi upload ảnh');</script>";
        }

        $newNameIMG = $url_new;
        // echo "<script>console.log($img_path)</script>";

    }

    // Kiểm tra nếu vị trí làm việc thay đổi thì cập nhật ngày nhận chức
    if ($quyen != $quyenmoi) {
        // Chỉ lấy ngày hôm nay nếu không có dữ liệu từ form
        if (empty($ngayNhanChuc)) {
            $ngayNhanChuc = date("Y-m-d");
        }
        $insert_lsutc = mysqli_query($conn, "INSERT INTO lsuthangchuc (idTK, vitricu, vitrimoi, luongcu, luongmoi, ngaynhamchuc, trangthai) 
                                                    VALUES('$idTK', '$quyen', '$quyenmoi', '$luongcu', '$luongmoi', '$ngayNhanChuc', 0)");
        // $updateQuyen = mysqli_query($conn, "UPDATE taikhoan SET idQUYEN='$quyenmoi' WHERE idTK='$idTK'");
        if (!$insert_lsutc) {
            echo "<script>console.log('Lỗi sửa chức vụ');</script>";
        }
    }
    

    // Lấy thông tin hiện tại của nhân viên
    $query_current = "SELECT SDT, EMAIL FROM taikhoan WHERE idTK = ?";
    $stmt_current = $conn->prepare($query_current);
    $stmt_current->bind_param("i", $idTK);
    $stmt_current->execute();
    $result_current = $stmt_current->get_result();
    $row_current = $result_current->fetch_assoc();

    $current_phone = $row_current["SDT"];
    $current_email = $row_current["EMAIL"];

    // Chỉ kiểm tra trùng nếu có sự thay đổi
    if ($phone !== $current_phone || $email !== $current_email) {
        $check_duplicate = "SELECT idTK FROM taikhoan WHERE (SDT = ? OR EMAIL = ?) AND idTK != ?";
        $stmt_check = $conn->prepare($check_duplicate);
        $stmt_check->bind_param("ssi", $phone, $email, $idTK);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            echo '<script>alert("⚠️ Số điện thoại hoặc email đã tồn tại!")</script>';
            return;
        }
    } 

    // Cập nhật thông tin tài khoản nếu không bị trùng
    $sql_update_tk = "UPDATE taikhoan SET 
        HOTEN = ?,  
        SDT = ?, 
        EMAIL = ?
    WHERE idTK = ?";

    $stmt_update_tk = $conn->prepare($sql_update_tk);
    $stmt_update_tk->bind_param("sssi", $hoten, $phone, $email, $idTK);

    // Cập nhật thông tin nhân viên
    $sql_update_nv = "UPDATE nhanvien SET 
                    GIOITINH = ?,  
                    NGAYSINH = ?, 
                    DIACHI = ?,
                    IMG = ?
                 WHERE idTK = ?";

    $stmt_update_nv = $conn->prepare($sql_update_nv);
    $stmt_update_nv->bind_param(
        "isssi",
        $gender,
        $date,
        $address,
        $newNameIMG,
        $idTK
    );
    if (!empty($img_temp)) {
        move_uploaded_file($img_temp, $img_path);
    }

    if ($stmt_update_nv->execute() && $stmt_update_tk->execute()) {
        echo "<script>alert('Cập nhật nhân viên thành công!');</script>
               window.location.reload(true)";
        // exit;
    } else {
        echo "<script>console.log('Lỗi Cập nhật nhân viên!');</script>";
    }
}

