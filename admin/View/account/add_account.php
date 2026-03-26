<?php
    include("../../admin/Controller/account/add_account.php");  // Chỉnh lại đường dẫn đúng
    $dsQuyen = getAllRoles();
?>

<title>Thêm tài khoản</title>
<link rel="stylesheet" href="../../css/admin/add_account.css">

<body>
    <div class="modal-content" id="add-acc">
        <h2>Thêm Tài Khoản</h2>

        <form method="post">
            <div class="input">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>

            <div class="input">
                <label>Mật khẩu:</label>
                <input type="password" name="password" required>
            </div>

            <div class="input">
                <label>Chọn nhóm quyền:</label>
                <select name="idQUYEN">
                    <?php foreach ($dsQuyen as $quyen): ?>
                        <?php if ($quyen['idQUYEN'] != 1 && $quyen['idQUYEN'] != 0): ?>
                            <option value="<?= $quyen['idQUYEN'] ?>"><?= $quyen['TENQUYEN'] ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="input">
                <label>Trạng thái:</label>
                <select name="trangthai">
                    <option value="1">Hoạt động</option>
                    <option value="0">Ngưng hoạt động</option>
                </select>
            </div>

            <div class="btn-add-account">
                <button type="button" class="btn-close-add" onclick="CloseAddAccount()">Trở lại</button>
                <input type="hidden" name="action" value="add_account">
                <input type="submit" value="Thêm Tài Khoản">
            </div>
        </form>

        <?php
            // Xử lý form ngay sau khi submit
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add_account') {
                addAccount();
            }
        ?>
    </div>
</body>