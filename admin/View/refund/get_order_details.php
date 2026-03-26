<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../../Model/db_connect.php';

$db = new Database();
$db->connect();

$idHD = $_GET['idHD'] ?? null;

if (!$idHD) {
    echo "Không tìm thấy đơn hàng.";
    exit();
}

$sql = "SELECT c.idCTSP, c.SOLUONG, s.DISCOUNT, s.IMG,
                s.TENSP, s.GIA, 
                t.HOTEN, t.SDT, 
                h.idHD,
                ct.MAUSAC, ct.DUNGLUONG
        FROM chitiethoadon c
        JOIN chitietsanpham ct ON c.idCTSP = ct.idCTSP
        JOIN sanpham s ON ct.idSP = s.idSP
        JOIN donhang h ON c.idHD = h.idHD
        JOIN taikhoan t ON h.idTK = t.idTK
        
        WHERE c.idHD = ?";
$stmt = $db->prepare($sql); // Use $db instead of $connect
$stmt->bind_param("i", $idHD);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0): ?>
    <h2>Chi tiết đơn hàng</h2>
    <table>
    <thead>
        <tr>
            <th>Mã đơn hàng</th>
            <th>Người đặt</th>
            <th>Số điện thoại</th>
            <th>Sản phẩm</th>
            <th>Màu sắc</th>
            <th>Dung lượng</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Tổng tiền sản phẩm</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['idHD']) ?></td>
                <td><?= htmlspecialchars($row['HOTEN']) ?></td>
                <td><?= htmlspecialchars($row['SDT']) ?></td>
                <td><?= htmlspecialchars($row['TENSP']) ?></td>
                <td><?= htmlspecialchars($row['MAUSAC'] ?? 'Không rõ') ?></td>
                <td><?= htmlspecialchars($row['DUNGLUONG'] ?? 'Không rõ') ?></td>
                <td><?= (int)$row['SOLUONG'] ?></td>
                <td><?= number_format((float)$row['GIA'] - ($row['GIA'] * $row['DISCOUNT'] / 100), 0, ',', '.') ?>đ</td>
                <td><?= number_format(((float)$row['GIA'] - ($row['GIA'] * $row['DISCOUNT'] / 100)) * (int)$row['SOLUONG'], 0, ',', '.') ?>đ</td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php else: ?>
    <p>Không tìm thấy chi tiết đơn hàng.</p>
<?php endif;

$stmt->close();
$db->close(); // Use $db instead of $connect
?>

<link rel="stylesheet" href="../../css/admin/refund_requests.css">

<script src="../../../js/admin/refund_requests.js"></script>