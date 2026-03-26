<?php      
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Kết nối cơ sở dữ liệu
    $conn = mysqli_connect("localhost", "root", "", "chdidong", 3306);
    if (!$conn) {
        die("Kết nối thất bại: " . mysqli_connect_error());
    }

    $listNgayNghi = mysqli_query($conn, 
        "SELECT t.HOTEN, t.idTK, n.TRANGTHAI, n.LYDO, n.NGAYNGHI, n.NGAYGUI, nv.IMG
                FROM ngaynghi n
                LEFT JOIN taikhoan t ON n.idNV = t.idTK
                LEFT JOIN nhanvien nv ON nv.idTK = t.idTK");

    // Lưu danh sách quyền vào mảng để sử dụng nhiều lần
    $requestLeave = [];
    while ($row = mysqli_fetch_assoc($listNgayNghi)) {
        $requestLeave[] = $row;
    }

?>

<!-- CSS chung -->
<link rel="stylesheet" href="../../css/admin/OneForAll.css">
<link rel="stylesheet" href="../../css/admin/Manager.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<body>
    <div class="header">    
        <div class="first-header">
            <p>Tất cả nghỉ phép</p>
        </div>
        <div class="second-header">
            <div class="second-header-main">
                <button class="home">
                    <a href="?page=employeeinfo"> 
                        <i class="fa-solid fa-house home-outline"></i>
                    </a>
                </button>
                <div class="line"></div>
                <button class="btn btn-add" onclick="openLeaveForm()">Thêm nghỉ phép</button>
            </div>
        </div>
    </div>

    <main class="main">
        <div class="container">
            <div class="filter">
                <input type="month" id="filter-month">
                <input type="text" id="search" placeholder="Tìm kiếm...">
                <button class="btn btn-search" onclick="filterTable()"><i class="fa-solid fa-search"></i></button>
            </div>

            <div class="table-content"> 
                <table>
                    <thead>
                        <tr>
                            <th>Mã NV</th>
                            <th>Họ tên</th>
                            <th>Ngày gửi</th>
                            <th>Ngày nghỉ</th>
                            <th>Lí do</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="leave-list">
                        <?php foreach ($requestLeave as $row):?>
                            <tr>
                                <td>NV<?= $row['idTK'] ?></td>
                                <td><?= $row['HOTEN'] ?></td>
                                <td><?= htmlspecialchars($row['NGAYGUI']) ?></td>
                                <td><?= htmlspecialchars($row['NGAYNGHI']) ?></td>
                                <td><?= htmlspecialchars($row['LYDO']) ?></td>
                                <td><?= htmlspecialchars($row['TRANGTHAI']) ?></td>
                                <td class='btn-action'>
                                    <input type="hidden" id="idTKInput" name="idTK" value="">
                                    <?php if ($row['TRANGTHAI'] == "Chưa duyệt"): ?>
                                        <button type="button" class="btn-update" name="updateRequestLeave"
                                            onclick="openFormUpdate(this)"
                                            data-idtk="<?= $row['idTK'] ?>"
                                            data-img="<?= $row['IMG'] ?>"
                                            data-nn="<?= $row['NGAYNGHI'] ?>">Cập nhật</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="return">
                    <a href="?page=employee">
                        <button type="button" class="btn-return">Quay lại</button>
                    </a>
                </div>
            </div>

            <div class="modal" id="modal">
                <div class="detail-asset" id="detail-asset">
                    <h1>Thông tin nghỉ phép</h1>
                    <div class="detail-asset-content">
                        <input type="hidden" name="idTK" id="popup-idTK" value="">
                        <input type="hidden" name='idnv' value=''>
                        <div class="asset-img">
                            <img id="imgnv" src="" alt="">
                        </div>
                        <div class="asset-info">
                            <p>
                                <strong>Tên nhân viên:</strong>
                                <span name='tennv'></span>
                            </p>
                            <p>
                                <strong>Ngày xin nghỉ:</strong>
                                <span name='ngaynghi'></span>
                            </p>
                            <p>
                                <strong>Lý do:</strong>
                                <span name='lydo'></span>
                            </p>
                        </div>
                    </div>
                    <div class="btn-action-asset">
                        <button name='huy' onclick="closePopup('detail', 'detail-popup')">Trở lại</button>
                        <button name='tuchoi' onclick="DuyetPhep('tuchoi')" class="btn-not">Từ chối</button>
                        <button name="duyet" onclick="DuyetPhep('duyet')" class="btn-sb">Duyệt</button>
                    </div>
                </div>

                <div id="leaveForm" class="popup-form">
                    <h1>Xin nghỉ phép</h1>
                        <div class="popup-form-content">
                            <div class="item-form">
                                <label for="ngaynghi">Ngày nghỉ:</label>
                                <input type="date" name="ngaynghi" id="ngaynghi" required>
                            </div>
                            <div class="item-form">
                                <label for="lydo">Lý do:</label>
                                <input type="text" name="lydo" id="lydo" required>
                            </div>
                            <div class="item-form">
                                <label for="nhanvien">Nhân viên:</label>
                                <select name="idNV" id="nhanvien">
                                    <?php
                                        $conn = mysqli_connect("localhost", "root", "", "chdidong", 3306);
                                        if (!$conn) {
                                            die("Kết nối thất bại: " . mysqli_connect_error());
                                        }

                                        $result = mysqli_query($conn, "SELECT idTK, HOTEN FROM taikhoan WHERE idQUYEN <> 0 AND idQUYEN <> 1");
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<option value='" . $row['idTK'] . "'>" . $row['idTK'] . "_" . $row['HOTEN'] . "</option>";
                                            }
                                        } else {
                                            echo "<option value=''>Không có nhân viên</option>";
                                        }
                                        mysqli_close($conn);
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="btn-action-item-form">
                            <button type="button" onclick="closeLeaveForm()">Hủy</button>
                            <button type="buttin" onclick="DuyetNghiPhep()">Xác nhận</button>
                        </div>
                </div>
            </div>
        </div>
    </main>
</body>

<script src="../../js/admin/Managers.js"></script>