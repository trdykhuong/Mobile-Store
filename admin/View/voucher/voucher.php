<?php
    // Đảm bảo đường dẫn đúng khi include file voucher.php
    include_once(__DIR__ . "/../../Controller/voucher/voucher.php");

    // Xử lý xóa voucher khi có id được gửi
    if (isset($_GET['delete_id'])) {
        $id = $_GET['delete_id'];

        $voucherModel = new VoucherModel();

        // Gọi phương thức xóa voucher (cập nhật trạng thái về 0)
        if ($voucherModel->deleteVoucher($id)) {
            $_SESSION['message'] = "Mã khuyến mãi đã được xóa thành công!";
        } else {
            $_SESSION['message'] = "Lỗi khi xóa mã khuyến mãi!";
        }

        // Chuyển hướng lại trang danh sách voucher sau khi xóa
        echo "
        <script>
            window.location.href = '?page=voucher';
        </script>";
        exit;
    }

    $voucherModel = new VoucherModel();
    $data = $voucherModel->getAllVouchers();
?>

<!-- CSS chung -->
<link rel="stylesheet" href="../../css/admin/OneForAll.css">
<link rel="stylesheet" href="../../css/admin/voucher.css">  
<link rel="stylesheet" href="../../css/admin/edit_voucher.css">

<body>
    <div class="header">    
        <div class="first-header">
            <p>Quản lý khuyến mãi</p>
        </div>
        <div class="second-header">
            <div class="second-header-main">
                <button class="home">
                    <a href="?page=employeeinfo"> 
                        <i class="fa-solid fa-house home-outline"></i>
                    </a>
                </button>
                <div class="line"></div>
                <button class="btn-add" onclick="openAddVoucher()">Thêm khuyến mãi</button>
            </div>
        </div>
    </div>

    <!-- Nội dung chính -->
    <main class="main">
        <div class='container'>
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert <?= (strpos($_SESSION['message'], 'thành công') !== false) ? 'success' : 'error'; ?>">
                    <?= $_SESSION['message']; ?>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <h2>Danh sách khuyến mãi</h2>
            <table>
                <thead>
                    <tr>
                        <th>Mã KM</th>
                        <th>Code</th>
                        <th>Giá trị</th>
                        <th>Số lượng</th>
                        <th>Ngày áp dụng</th>
                        <th>Hạn sử dụng</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?= $row['MAKHUYENMAI'] ?></td>
                        <td><?= $row['CODE'] ?></td>
                        <td><?= $row['GIATRI'] ?>đ</td>
                        <td><?= $row['SOLUONG'] ?></td>
                        <td><?= $row['NGAYAPDUNG'] ?></td>
                        <td><?= $row['HANSUDUNG'] ?></td>
                        <td><?= ($row['TRANGTHAI'] ? 'Đang áp dụng' : 'Ngưng áp dụng') ?></td>
                        <td>
                            <!-- Hiển thị liên kết Sửa nếu TRANGTHAI == 1 -->
                            <button type="button" class="open-edit" onclick="openEditVoucher(<?= $row['MAKHUYENMAI'] ?>)">Sửa</button>

                            <!-- Liên kết xóa voucher -->
                            <!-- <a href="?page=voucher&delete_id=<?= $row['MAKHUYENMAI'] ?>" onclick="return confirm('Xóa thật không?')">Xóa</a> -->
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="modal" id="modal-add">
                <!-- Thêm voucher mới css ở add_voucher.css-->
                <div class="add-voucher" id="add-voucher">
                    <?php include "../../admin/View/voucher/add_voucher.php" ?>
                </div>
            </div>

            <div class="modal" id="modal-edit">
                <!-- Sửa voucher css ở edit_voucher.css-->
                <?php include "../../admin/Controller/voucher/edit_voucher.php" ?>
                <div class="edit-voucher" id="edit-voucher"></div>
            </div>
        </div>
    </main>
</body>

<script src="../../js/admin/voucher.js"></script>