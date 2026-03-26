<?php
    include("../../admin/Controller/account/account.php");
    $accounts = getAllAccounts();
?>

<title>Danh sách tài khoản</title>
<!-- CSS chung -->
<link rel="stylesheet" href="../../css/admin/OneForAll.css">
<link rel="stylesheet" href="../../css/admin/account.css">

<body>
    <div class="header">    
        <div class="first-header">
            <p>Danh sách tài khoản</p>
        </div>
        <div class="second-header">
            <div class="second-header-main">
                <button class="home">
                    <a href="?page=employeeinfo"> 
                        <i class="fa-solid fa-house home-outline"></i>
                    </a>
                </button>
                <div class="line"></div>
                <button class="btn-AddAccount" onclick="OpenAddAccount()">Thêm tài khoản</button>
            </div>
        </div>
    </div>

    <!-- Nội dung chính -->
    <main class="main">
        <div class='container'>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Họ Tên</th>
                        <th>Email</th>
                        <th>Quyền</th>
                        <th>Trạng Thái</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (count($accounts) > 0): ?>
                        <?php foreach ($accounts as $row): ?>
                            <tr>
                                <td class="id"><?= $row['idTK'] ?></td>
                                <td><?= htmlspecialchars($row['USERNAME']) ?></td>
                                <td><?= htmlspecialchars($row['HOTEN']) ?></td>
                                <td><?= htmlspecialchars($row['EMAIL']) ?></td>
                                <td class="role"><?= $row['TENQUYEN'] ?></td>
                                <td class="<?= $row['TRANGTHAI'] ? 'active' : 'inactive' ?> state">
                                    <?= $row['TRANGTHAI'] ? 'Hoạt động' : 'Ngưng hoạt động' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center;">Không có tài khoản nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="modal" id="modal">
            <?php include('../../admin/View/account/add_account.php'); ?>   
        </div>
    </main>
</body>

<script src="../../js/admin/account.js"></script>