<?php
    include_once(__DIR__ . "/../../Controller/voucher/voucher.php");
    $voucherModel = new VoucherModel();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["add-voucher-new-new"])) {
            
            $code = $_POST["code"];
            $giatri = $_POST["giatri"];
            $soluong = $_POST["soluong"];
            $ngayapdung = $_POST["ngayapdung"];
            $hansudung = $_POST["hansudung"];
            $trangthai = $_POST["trangthai"];

            if ($voucherModel->addVoucher($code, $giatri, $soluong, $ngayapdung, $hansudung, $trangthai)) {
                $_SESSION['message'] = "Thêm mã khuyến mãi thành công!";
                echo "
                <script>
                    window.location.href = '?page=voucher';
                </script>";
                exit();
            } else {
                echo "<script>alert('Lỗi khi thêm mã khuyến mãi!');</script>";
            }
        }
    }
?>

<link rel="stylesheet" href="../../css/admin/add_voucher.css">

<form method="POST">
    <h1>Thêm mã khuyến mãi</h1>
    <div class="form-content">
        <div class="input">
            <strong>Code:</strong>
            <input type="text" name="code" required>
        </div>
        <div class="input">
            <strong>Giá trị(đ):</strong>
            <input type="number" name="giatri" required>
        </div>
        <div class="input">
            <strong>Số lượng:</strong>
            <input type="number" name="soluong" required>  
        </div>
        <div class="input">
            <strong>Ngày áp dụng:</strong>
            <input type="date" name="ngayapdung" required>  
        </div>
        <div class="input">
            <strong>Hạn sử dụng:</strong>
            <input type="date" name="hansudung" required>
        </div>
        <div class="input">
            <strong>Trạng thái:</strong>
            <select name="trangthai">
                <option value="1">Đang áp dụng</option>
                <option value="0">Ngưng áp dụng</option>
            </select>
        </div>

        <div class="footer-add">
            <button type="button" onclick="closeAddVoucher()">Trở lại</button>
            <button type="submit" class="btn-add-new-voucher" name="add-voucher-new-new">Thêm</button>
        </div>
    </div>
</form>