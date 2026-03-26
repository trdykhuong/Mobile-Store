<?php
    include_once(__DIR__ . "/../../Controller/voucher/voucher.php");
    $voucherModel = new VoucherModel();

    if (!isset($_GET["id"]) || empty($_GET["id"])) {
        echo "<p>ID không hợp lệ.</p>";
        exit();
    }

    $id = $_GET["id"];  
    $voucher = $voucherModel->getVoucherById($id);  
    if (!$voucher) {
        echo "<p>Không tìm thấy mã khuyến mãi.</p>";
        exit();
    }
?>

<link rel="stylesheet" href="../../css/admin/edit_voucher.css">

<form method="POST">
    <h1>Sửa Mã Khuyến Mãi</h1>
    <div class="form-content">
        <div class="input">
            <input type="hidden" name="idKM" value="<?= $id?>">
            <label for="code">Code:</label>
            <input type="text" id="code" name="code" value="<?= htmlspecialchars($voucher['CODE']) ?>" required>
        </div>
        <div class="input">
            <label for="giatri">Giá trị(đ):</label>
            <input type="number" id="giatri" name="giatri" value="<?= htmlspecialchars($voucher['GIATRI']) ?>" required>
        </div>
        <div class="input">
            <label for="soluong">Số lượng:</label>
            <input type="number" id="soluong" name="soluong" value="<?= htmlspecialchars($voucher['SOLUONG']) ?>" required>
        </div>
        <div class="input">
            <label for="ngayapdung">Ngày áp dụng:</label>
            <input type="date" id="ngayapdung" name="ngayapdung" value="<?= htmlspecialchars($voucher['NGAYAPDUNG']) ?>" required>
        </div>
        <div class="input">
            <label for="hansudung">Hạn sử dụng:</label>
            <input type="date" id="hansudung" name="hansudung" value="<?= htmlspecialchars($voucher['HANSUDUNG']) ?>" required>
        </div>
        <div class="input">
            <label for="trangthai">Trạng thái:</label>
            <select id="trangthai" name="trangthai">
                <option value="1" <?= $voucher['TRANGTHAI'] == 1 ? 'selected' : '' ?>>Đang áp dụng</option>
                <option value="0" <?= $voucher['TRANGTHAI'] == 0 ? 'selected' : '' ?>>Ngưng áp dụng</option>
            </select>
        </div>

        <div class="footer-edit">
            <button type="button" onclick="closeEditVoucher()">Quay lại</button>
            <button type="submit" class="btn-edit-voucher" name="edit-voucher-new-here">Cập nhật</button>
        </div>
    </div>
</form>