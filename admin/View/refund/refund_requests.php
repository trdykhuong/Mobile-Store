<?php
    include_once(__DIR__ . "../../../../Model/db_connect.php");

    $sql = "SELECT r.id, r.idHD, r.idTK, r.amount, r.ngayhoan, r.trangthai, 
                    d.THANHTIEN, d.NGAYMUA, t.hoten, t.email, tt.STATUS 
                FROM hoantien r
                JOIN donhang d ON r.idHD = d.idHD
                JOIN taikhoan t ON r.idTK = t.idTK
                JOIN trangthaihoantien tt ON r.trangthai = tt.idSTATUS
                WHERE r.trangthai IN (0,1)
                ORDER BY r.id DESC";

    $result = $connect->query($sql);
?>

<!-- CSS chung -->
<link rel="stylesheet" href="../../css/admin/OneForAll.css">
<link rel="stylesheet" href="../../css/admin/refund_requests.css">

<body>
    <div class="header">    
        <div class="first-header">
            <p>Quản lý yêu cầu hoàn tiền</p>
        </div>
        <div class="second-header">
            <div class="second-header-main">
                <button class="home">
                    <a href="?page=employeeinfo"> 
                        <i class="fa-solid fa-house home-outline"></i>
                    </a>
                </button>
                <div class="line"></div>
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
                        <th>Mã đơn hàng</th>
                        <th>ID Tài khoản</th>
                        <th>Giá trị đơn hàng</th>
                        <th>Ngày yêu cầu</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="refund-row" data-id="<?= $row['idHD'] ?>" style="cursor: pointer;">
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['idHD'] ?></td>
                            <td><?= $row['idTK'] ?></td>
                            <td><?= number_format($row['amount'], 0, ',', '.') ?> VND</td>
                            <td><?= $row['ngayhoan'] ?></td>
                            <td><?= $row['STATUS'] ?></td>
                            <td>
                                <button class="btn-approve" data-id="<?= $row['id'] ?>" <?php if ($row['trangthai'] == 1 || $row['trangthai'] == 2) echo 'disabled'; ?>>Duyệt</button>
                                <button class="btn-reject" data-id="<?= $row['id'] ?>" <?php if ($row['trangthai'] == 1 || $row['trangthai'] == 2) echo 'disabled'; ?>>Từ chối</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div id="order-detail-modal" class="modal">
                <div class="modal-content">
                    <div id="order-detail-content" class="order-detail-content">

                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

<script src="../../js/admin/refund_requests.js"></script>