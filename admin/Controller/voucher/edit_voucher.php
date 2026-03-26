<?php 
    if (isset($_POST["edit-voucher-new-here"])) {
        $id = $_POST["idKM"];
        $code = $_POST["code"];
        $giatri = $_POST["giatri"];
        $soluong = $_POST["soluong"];
        $ngayapdung = $_POST["ngayapdung"];
        $hansudung = $_POST["hansudung"];
        $trangthai = $_POST["trangthai"];

        if ($voucherModel->updateVoucher($id, $code, $giatri, $soluong, $ngayapdung, $hansudung, $trangthai)) {
            $_SESSION['message'] = "Cập nhật mã khuyến mãi thành công!";
            echo "
            <script>
                window.location.href = '?page=voucher';
            </script>";
            exit();
        } else {
            echo "<script>alert('Lỗi khi cập nhật mã khuyến mãi!');</script>";
        }
    }  
?>