<?php
$connect = new mysqli("localhost", "root", "", "chdidong");

if ($connect->connect_error) {
    die("Lỗi kết nối: " . $connect->connect_error);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$idTK = $_SESSION['id'] ?? 0; // Ensure `id` is retrieved from the session
if ($idTK == 0) {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_refund'])) {
        echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập.']);
        exit();
    } else {
        die("Bạn chưa đăng nhập.");
    }
}

// Xử lý hoàn tiền
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_refund'])) {
    $idHD = intval($_POST['idHD'] ?? 0);
    $amount = floatval($_POST['amount'] ?? 0);

    // Kiểm tra thông tin đầu vào hợp lệ
    if ($idHD <= 0 || $amount <= 0) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
        exit();
    }

    // Kiểm tra đơn hàng tồn tại và thuộc tài khoản người dùng
    $stmt = $connect->prepare("SELECT THANHTIEN FROM donhang WHERE idHD = ? AND idTK = ?");
    $stmt->bind_param("ii", $idHD, $idTK);
    $stmt->execute();
    $stmt->bind_result($order_amount);
    $found = $stmt->fetch();
    $stmt->close();

    if (!$found) {
        echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại hoặc không thuộc về tài khoản hiện tại.']);
        exit();
    }

    // Kiểm tra số tiền yêu cầu hoàn có hợp lệ không
    if ($amount > $order_amount) {
        echo json_encode(['success' => false, 'message' => 'Số tiền yêu cầu hoàn vượt quá giá trị đơn hàng.']);
        exit();
    }

    // Kiểm tra đã có yêu cầu hoàn cho đơn hàng này chưa
    $stmt = $connect->prepare("SELECT COUNT(*) FROM hoantien WHERE idHD = ?");
    $stmt->bind_param("i", $idHD);
    $stmt->execute();
    $stmt->bind_result($refund_count);
    $stmt->fetch();
    $stmt->close();

    if ($refund_count > 0) {
        echo json_encode(['success' => false, 'message' => 'Đơn hàng này đã có yêu cầu hoàn tiền.']);
        exit();
    }

    // Ghi nhận yêu cầu hoàn tiền mới
    $ngayhoan = date('Y-m-d'); // Ngày hoàn tiền
    $trangthai = 0; // Chờ xử lý

    $stmt = $connect->prepare("INSERT INTO hoantien (idHD, idTK, amount, ngayhoan, trangthai) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iidsi", $idHD, $idTK, $amount, $ngayhoan, $trangthai);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Yêu cầu hoàn tiền đã được gửi thành công.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể gửi yêu cầu hoàn tiền. Vui lòng thử lại sau.']);
    }

    $stmt->close();
    exit();
}

// Lấy danh sách trạng thái đơn hàng
$sql_statuses = "SELECT idSTATUS, STATUS FROM trangthaidonhang";
$result_statuses = $connect->query($sql_statuses);
$statuses = [];
while ($row_status = $result_statuses->fetch_assoc()) {
    $statuses[$row_status['idSTATUS']] = $row_status['STATUS'];
}

// Sửa truy vấn để lấy tất cả đơn hàng, bao gồm cả idTHANHTOAN = 1
$sql = "SELECT h.idHD, h.NGAYMUA, h.TRANGTHAI, tdh.STATUS AS TEN_TRANGTHAI, tdh.idSTATUS, 
               h.THANHTIEN, h.idVC, h.diachi, h.idTHANHTOAN, h.SDT_DH,
               c.idCTSP, c.SOLUONG,
               s.idSP, s.TENSP, s.GIA, s.IMG, s.DISCOUNT,
               ct.MAUSAC, ct.DUNGLUONG, ct.DIEUCHINHGIA,
               v.GIAVANCHUYEN,
               k.GIATRI,
               t.sdt, t.hoten,
               pt.TENPHUONGTHUC
        FROM donhang h
        JOIN chitiethoadon c ON h.idHD = c.idHD
        JOIN chitietsanpham ct ON c.idCTSP = ct.idCTSP
        JOIN sanpham s ON ct.idSP = s.idSP
        JOIN dvvanchuyen v ON h.idVC = v.idVC
        LEFT JOIN khuyenmai k ON h.MAKHUYENMAI = k.MAKHUYENMAI
        JOIN taikhoan t ON h.idTK = t.idTK
        JOIN trangthaidonhang tdh ON h.TRANGTHAI = tdh.idSTATUS
        JOIN ptthanhtoan pt ON h.idTHANHTOAN = pt.idTHANHTOAN  
        WHERE h.idTK = ? AND h.idTHANHTOAN IN (1, 2) 
        ORDER BY h.idHD DESC";

$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $idTK);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];

while ($row = $result->fetch_assoc()) {
    $idHD = $row['idHD'];

    // Gom nhóm các đơn hàng
    if (!isset($orders[$idHD])) {
        $orders[$idHD] = [
            'info' => [
                'idHD' => $row['idHD'],
                'NGAYMUA' => $row['NGAYMUA'],
                'TRANGTHAI_ID' => $row['idSTATUS'],   // Lưu số trạng thái (1, 2, 3, 4, 5)
                'TRANGTHAI_TEN' => $row['TEN_TRANGTHAI'],  // Lưu tên trạng thái (Chờ xác nhận, Đang giao,...) 
                'THANHTIEN' => $row['THANHTIEN'],
                'idVC' => $row['idVC'],
                'GIAVANCHUYEN' => $row['GIAVANCHUYEN'],
                'GIATRI' => $row['GIATRI'],
                'DISCOUNT' => $row['DISCOUNT'],
                'diachi' => $row['diachi'],
                'sdt' => $row['SDT_DH'],    // Lấy số điện thoại
                'hoten' => $row['hoten'],
                'TENPHUONGTHUC' => $row['TENPHUONGTHUC'],
                'idTHANHTOAN' => $row['idTHANHTOAN'],
                'MAUSAC' => $row['MAUSAC'],
                'DUNGLUONG' => $row['DUNGLUONG'],
                'DIEUCHINHGIA' => $row['DIEUCHINHGIA'],
            ],
            'items' => []
        ];
    }

    // Thêm từng sản phẩm vào đơn hàng
    $orders[$idHD]['items'][] = [
        'idSP' => $row['idSP'],
        'idCTSP' => $row['idCTSP'],
        'TENSP' => $row['TENSP'],
        'SOLUONG' => $row['SOLUONG'],
        'GIA' => $row['GIA'],
        'IMG' => $row['IMG'],
        'DISCOUNT' => $row['DISCOUNT'],
        'MAUSAC' => $row['MAUSAC'],
        'DUNGLUONG' => $row['DUNGLUONG'],
        'DIEUCHINHGIA' => $row['DIEUCHINHGIA'],
    ];
}

$stmt->close();

?>

<link rel="stylesheet" href="../../css/user/user-purchase-history.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<body>
    <h1>Lịch sử mua hàng</h1>

    <!-- Lọc hóa đơn -->
    <div class="nav-info">
        <ul id="status-filter">
            <li data-status="all" class="active">Tất cả</li>
            <?php foreach ($statuses as $status): ?>
                <li data-status="<?= htmlspecialchars($status) ?>"><?= htmlspecialchars($status) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Form hóa đơn -->
    <div class="purchase-history-content">
        <table class="purchase-history-table">
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <tr class="order-row" data-status="<?= $order['info']['TRANGTHAI_TEN'] ?>">
                        <td>
                            <div class="main-content">
                                <!-- Nội dung đầu -->
                                <div class="first-content">
                                    <div class="order-id">
                                        Đơn hàng: <span><?= $order['info']['idHD'] ?></span>
                                    </div>
                                    <div class="order-status">
                                        <span><?= $order['info']['TRANGTHAI_TEN'] ?></span>
                                    </div>
                                </div>

                                <!-- Danh sách sản phẩm trong đơn hàng -->
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="second-content">
                                        <img src="../../images/products/<?= $item['IMG'] ?>">
                                        <div class="order-product">
                                            <h3><?= $item['TENSP'] ?></h3>
                                            <p>Số lượng:
                                                <span class="number-of-products-ordered"><?= $item['SOLUONG'] ?></span>
                                            </p>
                                            <p>Giá tiền:
                                            <span class="product-price-order"><?= number_format($item['GIA'] + $item['DIEUCHINHGIA'] - (($item['GIA'] + $item['DIEUCHINHGIA']) * ($item['DISCOUNT'] ?? 0) / 100), 0, ',', '.') ?></span>đ
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <!-- Nội dung thứ ba -->
                                <div class="third-content">
                                    <div class="total-order-amount">
                                        <h3>Tổng tiền đơn hàng</h3>
                                        <div class="price-and-detail">
                                            <p>
                                                <span><?= number_format($order['info']['THANHTIEN'], 0, ',', '.') ?></span>đ
                                            </p>
                                            <button class="btn-detail" data-id="<?= $order['info']['idHD'] ?>">Chi tiết</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Không có đơn hàng nào.</p>
            <?php endif; ?>
        </table>
    </div>

    <!-- Chi tiết đơn hàng -->
    <?php foreach ($orders as $order): ?>
        <div class="form-order-detail">
            <div class="order-detail" data-id="<?= $order['info']['idHD'] ?>" style="z-index: <?= $order['info']['idHD'] ?>;">
                <div class="first-content-detail">
                    <div class="order-id-detail">
                        Đơn hàng: <span><?= $order['info']['idHD'] ?></span>
                    </div>
                    <div class="order-status-detail"><?= $order['info']['TRANGTHAI_TEN'] ?></div>
                </div>

                <div class="order-time-detail">
                    <i class="fas fa-calendar-alt"></i>
                    <p>Ngày đặt hàng: <span><?= $order['info']['NGAYMUA'] ?></span></p>
                </div>

                <div class="orderer-information">
                    <h3>Thông tin khách hàng</h3>
                    <div class="orderer-information-name">
                        <i class="fas fa-user"></i>
                        <p>Người nhận: <span><?= htmlspecialchars($order['info']['hoten']) ?></span></p>
                    </div>
                    <div class="orderer-information-phone">
                        <i class="fas fa-phone"></i>
                        <p>Số điện thoại: <span><?= htmlspecialchars($order['info']['sdt']) ?></span></p>
                    </div>
                    <div class="orderer-information-address">
                        <i class="fas fa-map-marker-alt"></i>
                        <p>Địa chỉ: <span><?= htmlspecialchars($order['info']['diachi']) ?></span></p>
                    </div>
                    <div class="orderer-information-payment">
                        <i class="fas fa-money"></i>
                        <p>Hình thức thanh toán: <span><?= htmlspecialchars($order['info']['TENPHUONGTHUC']) ?></span></p>
                    </div>
                </div>

                <?php foreach ($order['items'] as $item): ?>
                    <div class="second-content-detail">
                        <div class="second-content-detail-left">
                            <img src="../../images/products/<?= $item['IMG'] ?>" alt="<?= $item['TENSP'] ?>">
                            <div class="order-product-detail">
                                <h3><?= $item['TENSP'] ?></h3>
                                <p>Màu sắc: <?= $item['MAUSAC'] ?? 'Không xác định' ?></p>
                                <p>Dung lượng: <?= $item['DUNGLUONG'] ?? 'Không xác định' ?></p>
                                <p>Số lượng: <?= $item['SOLUONG'] ?></p>
                                <p>Giá tiền: <?= number_format($item['GIA'] + $item['DIEUCHINHGIA'] - (($item['GIA'] + $item['DIEUCHINHGIA']) * ($item['DISCOUNT'] ?? 0) / 100), 0, ',', '.') ?>đ</p>
                            </div>
                        </div>

                        <!-- Nút đánh giá -->
                        <div class="second-content-detail-right">
                            <?php if ($order['info']['TRANGTHAI_ID'] == 4): ?>
                                <div class="review-form">
                                    <button class="btn-review"
                                        data-id="<?= $item['idCTSP'] ?>"
                                        data-img="../../images/products/<?= $item['IMG'] ?>"
                                        data-name="<?= $item['TENSP'] ?>"
                                        onclick="openReviewModal(this)">
                                        Viết đánh giá
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="third-content-detail">
                    <div class="total-order-amount-detail">
                        <table>
                            <tr>
                                <td>
                                    Tổng tiền sản phẩm
                                    <p>
                                        <span><?= number_format($order['info']['THANHTIEN'] - $order['info']['GIAVANCHUYEN'] + $order['info']['GIATRI'], 0, ',', '.') ?></span>đ
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Phí vận chuyển
                                    <p>
                                        <span><?= number_format($order['info']['GIAVANCHUYEN'], 0, ',', '.') ?></span>đ
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Ưu đãi/Giảm giá
                                    <p>
                                        <span><?= number_format($order['info']['GIATRI'], 0, ',', '.') ?></span>đ
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="total-price-detail">
                                    Tổng tiền đơn hàng
                                    <p>
                                        <span><?= number_format($order['info']['THANHTIEN'], 0, ',', '.') ?></span>đ
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="fourth-content-detail">
                        <button class="btn-return"><i class="fas fa-arrow-left"></i> Quay lại</button>
                        <?php if ($order['info']['TRANGTHAI_ID'] == 1): ?>
                            <button class="btn-cancel btn-cancel-order" data-id="<?= $order['info']['idHD'] ?>">Hủy đơn hàng</button>
                        <?php endif; ?>
                        <?php if ($order['info']['TRANGTHAI_ID'] == 2): ?>
                            <button class="btn-cancel btn-cancel-order" data-id="<?= $order['info']['idHD'] ?>">Hủy đơn hàng</button>
                        <?php endif; ?>
                        <?php if ($order['info']['TRANGTHAI_ID'] == 3): ?>
                            <button class="btn-received" data-id="<?= $order['info']['idHD'] ?>">Đã nhận hàng</button>
                        <?php endif; ?>
                        <?php if ($order['info']['TRANGTHAI_ID'] == 5 && $order['info']['idTHANHTOAN'] == 2): ?>
                            <button class="btn-refund" data-id="<?= $order['info']['idHD'] ?>">Yêu cầu hoàn tiền</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- FORM DANH GIA SP -->
    <div id="review-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Đánh giá của bạn</h3>
                <span class="close-modal" onclick="closeReviewModal()"><i class="fa fa-times"></i></span>
            </div>
            <input type="hidden" id="selected-product-id" value="">
            <img src="" alt="Ten SP">
            <span class="modal-title">Bạn cảm thấy sản phẩm như thế nào?</span>

            <div class="modal-rating">
                <span class="modal-star" data-value="1"></span>
                <span class="modal-star" data-value="2"></span>
                <span class="modal-star" data-value="3"></span>
                <span class="modal-star" data-value="4"></span>
                <span class="modal-star" data-value="5"></span>
            </div>

            <div id="modal-rating-label">Chưa đánh giá</div>

            <div class="modal-to-rate">
                <p>Đánh giá của bạn</p>
                <textarea id="review-comment" placeholder="Nhận xét của bạn..."></textarea>
            </div>

            <button class="sumit-evaluation">
                <i class="fa fa-check"></i> Gửi đánh giá
            </button>

            <input type="hidden" id="selected-rating" value="0">
        </div>
    </div>
</body>

<script src="../../js/user/user-purchase-history.js"></script>