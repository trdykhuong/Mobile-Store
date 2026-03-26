<?php
    ob_start(); // Bật bộ nhớ đệm
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_POST['action'] === 'add_new_role') {
            include_once(__DIR__ . "/../../Controller/role/role_add.php");
        } elseif ($_POST['action'] === 'update_permission') {
            include_once(__DIR__ . "/../../Controller/role/role_edit.php");
        }
    }

    include_once(__DIR__ . "/../../Controller/role/role.php");

    // Xử lý thêm quyền mới
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        if ($_POST['action'] === 'add_new_role') {
            $tenquyen = $_POST['tenquyen'];
            $new_permissions = $_POST['new_role_permissions'] ?? [];

            $new_role_id = themQuyenMoi($tenquyen);

            if ($new_role_id) {
                foreach ($new_permissions as $permission) {
                    [$idCN, $thaotac] = explode('_', $permission);
                    themPhanQuyen($new_role_id, $idCN, $thaotac);
                }

                echo "<script>window.location.href = '?page=role&role={$new_role_id}&status=add_success';</script>";
                exit();            
            } else {
                echo '<div class="alert alert-danger show hide auto-hide">Quyền đã tồn tại!</div>';
            }
        }

        // Xử lý cập nhật quyền hiện tại
        if ($_POST['action'] === 'update_permission') {
            $selected_role = $_POST['role'];
            $selected_permissions = $_POST['chucnang'] ?? [];

            capNhatPhanQuyen($selected_role, $selected_permissions);

            echo "<script>window.location.href = '?page=role&role={$selected_role}&status=success';</script>";
            exit();        
        }
    }

    // Thông báo nếu có
    if (isset($_GET['status'])) {
        if ($_GET['status'] === 'success') {
            echo '<div class="alert alert-success show hide auto-hide">Cập nhật quyền thành công!</div>';
        } elseif ($_GET['status'] === 'add_success') {
            echo '<div class="alert alert-success show hide auto-hide">Thêm quyền mới thành công!</div>';
        }
    }

    // Lấy danh sách vai trò
    $selected_role = $_REQUEST['role'] ?? ($roles[0]['idQUYEN'] ?? null);
?>


<title>Quản Lý Phân Quyền</title>
<!-- CSS chung -->
<link rel="stylesheet" href="../../css/admin/OneForAll.css">
<link rel="stylesheet" href="../../css/admin/role.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<body>
    <div class="header">    
        <div class="first-header">
            <p>Quản lý phân quyền</p>
        </div>
        <div class="second-header">
            <div class="second-header-main">
                <button class="home">
                    <a href="?page=employeeinfo"> 
                        <i class="fa-solid fa-house home-outline"></i>
                    </a>
                </button>
                <div class="line"></div>
                <button class="btn-add-role" id="btn-add-role" onclick="OpenAddRole()">Thêm chức vụ</button>
            </div>
        </div>
    </div>

    <!-- Nội dung chính -->
    <main class="main">
        <div class="container">
            <!-- Form phân quyền hiện tại -->
            <form method="POST">
                <input type="hidden" name="action" value="update_permission">
                <div class="form-group-header">
                    <label>Chọn Vai Trò:</label>
                    <select class="form-control" name="role" id="role">
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['idQUYEN'] ?>" <?= $selected_role == $role['idQUYEN'] ? 'selected' : '' ?>>
                                <?= $role['TENQUYEN'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group-content">
                    <div class="form-group-content-header">
                        <label>Chức Năng và Thao Tác:</label>
                        <div class="btn-update">
                            <button type="submit" class="btn-update-role">Cập nhật quyền</button>
                        </div>
                    </div>
                    <!-- dùng giao diện cũ thì ẩn 109-115 -->
                    <div class="role-view">
                        <p class="name-role">Tên chức năng</p>
                        <p class="name-role-detail">Quyền Xem</p>
                        <!-- <p class="name-role-detail">Quyền Thêm</p>
                        <p class="name-role-detail">Quyền Sửa</p>
                        <p class="name-role-detail">Quyền Xóa</p> -->
                    </div>
                    <div class="content-role">
                        <?php foreach ($chucnang as $cn): ?>
                            <?php
                            $view_checked = $them_checked = $sua_checked = $xoa_checked = false;
                            foreach ($phanquyen as $pq) {
                                if ($pq['idQUYEN'] == $selected_role && $pq['idCN'] == $cn['idCN']) {
                                    switch ($pq['THAOTAC']) {
                                        case 'XEM': $view_checked = true; break;
                                        case 'THEM': $them_checked = true; break;
                                        case 'SUA': $sua_checked = true; break;
                                        case 'XOA': $xoa_checked = true; break;
                                    }
                                }
                            }
                            ?>
                            <div class="role-card">
                                <div class="role-card-header"><?= $cn['TENCN'] ?></div>
                                <div class="role-card-body">
                                    <div class="body-detail">
                                        <input type="checkbox" name="chucnang[]" value="<?= $cn['idCN'] ?>_XEM" <?= $view_checked ? 'checked' : '' ?>>
                                        <!-- <p class="value-name">Xem</p> -->
                                    </div>
                                    <!-- <div class="body-detail">
                                        <input type="checkbox" name="chucnang[]" value="<?= $cn['idCN'] ?>_THEM" <?= $them_checked ? 'checked' : '' ?>>
                                        <p class="value-name">Thêm</p> 
                                    </div>
                                    <div class="body-detail">
                                        <input type="checkbox" name="chucnang[]" value="<?= $cn['idCN'] ?>_SUA" <?= $sua_checked ? 'checked' : '' ?>>
                                        <p class="value-name">Sửa</p>
                                    </div>
                                    <div class="body-detail">
                                        <input type="checkbox" name="chucnang[]" value="<?= $cn['idCN'] ?>_XOA" <?= $xoa_checked ? 'checked' : '' ?>>
                                        <p class="value-name">Xóa</p>
                                    </div> -->
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </form>

            <!-- Form thêm quyền (ẩn ban đầu) -->
            <div class="modal" id="modal">
                <div id="form-add-role" class="form-add-role">
                    <h4>Thêm Quyền Mới</h4>
                    <form method="POST">
                        <input type="hidden" name="action" value="add_new_role">
                        <div class="form-group-header">
                            <label>Tên Quyền:</label>
                            <input type="text" class="form-control" name="tenquyen" required>
                        </div>
                        <div class="form-group-content-add">
                            <label>Chọn Chức Năng và Thao Tác:</label>
                            <div class="content-role">
                                <?php foreach ($chucnang as $cn): ?>
                                    <div class="role-card-add">
                                        <div class="role-card-header-add"><?= $cn['TENCN'] ?></div>
                                        <div class="role-card-body-add">
                                            <div class="body-detail-add">
                                                <input type="checkbox" name="new_role_permissions[]" value="<?= $cn['idCN'] ?>_XEM">
                                                <p class="value-name">Xem</p>
                                            </div>
                                            <!-- <div class="body-detail">
                                                <input type="checkbox" name="new_role_permissions[]" value="<?= $cn['idCN'] ?>_THEM">
                                                <p class="value-name">Thêm</p>
                                            </div>
                                            <div class="body-detail">
                                            <input type="checkbox" name="new_role_permissions[]" value="<?= $cn['idCN'] ?>_SUA">
                                                <p class="value-name">Sửa</p>
                                            </div>
                                            <div class="body-detail">
                                            <input type="checkbox" name="new_role_permissions[]" value="<?= $cn['idCN'] ?>_XOA">
                                                <p class="value-name">Xóa</p>
                                            </div> -->
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="btn-form-add">
                            <button type="button" class="btn-return-add" onclick="CloseAddRole()">Trở lại</button>
                            <button type="submit" class="btn-add-submit">Thêm quyền</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

<script >
$(document).ready(function() {
    $('#btn-them-quyen').click(function() {
        $('#form-them-quyen').toggle();
    });

    $('#role').change(function() {
        window.location.href = "?page=role&role=" + $(this).val();
    });
});

const modal = document.getElementById('modal');
const formAddRole = document.getElementById('form-add-role');

document.addEventListener('click', (e)=> {
    if (modal.contains(e.target) && !formAddRole.contains(e.target)) {
        OpenAddRole();
        CloseAddRole();
    }
})

function OpenAddRole() {
    modal.classList.add('open-modal');
    formAddRole.classList.add('open-form-add-role');
    document.querySelector(".hidden-log-out").classList.add("active");
}

function CloseAddRole() {
    modal.classList.remove('open-modal');
    formAddRole.classList.remove('open-form-add-role');
    document.querySelector(".hidden-log-out").classList.remove("active");
}
</script>