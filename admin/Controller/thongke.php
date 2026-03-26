<?php
header('Content-Type: application/json; charset=utf-8');
include_once(__DIR__ . "/../../Model/db_connect.php");



$db = new Database();
$conn = $db->connect();

// Nhận tham số từ request (từ ngày - đến ngày)
$fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : date('Y-01-01');
$toDate = isset($_GET['toDate']) ? $_GET['toDate'] : date('Y-12-31');


// Thống kê doanh thu theo tháng (có lọc theo ngày)
$sql_revenue = "SELECT MONTH(NGAYMUA) AS month, SUM(THANHTIEN) AS revenue 
                FROM donhang 
                WHERE NGAYMUA BETWEEN ? AND ?
                GROUP BY month 
                ORDER BY month";

$stmt = $conn->prepare($sql_revenue);
$stmt->bind_param("ss", $fromDate, $toDate);
$stmt->execute();
$result_revenue = $stmt->get_result();

$months = [];
$revenues = [];

while ($row = $result_revenue->fetch_assoc()) {
    $months[] = "Tháng " . $row['month'];
    $revenues[] = $row['revenue'];
}
$stmt->close();

// Thống kê top 5 sản phẩm bán chạy (có lọc theo ngày)
$sql_top_products = "SELECT sp.TENSP, SUM(ct.SOLUONG) AS total_sold 
                     FROM chitiethoadon ct 
                     JOIN donhang dh ON ct.idHD = dh.idHD
                     JOIN chitietsanpham ctsp on ct.idCTSP = ctsp.idCTSP
                     JOIN sanpham sp ON ctsp.idSP = sp.idSP 
                     WHERE dh.NGAYMUA BETWEEN ? AND ?
                     GROUP BY ct.idCTSP 
                     ORDER BY total_sold DESC 
                     LIMIT 5";

$stmt = $conn->prepare($sql_top_products);
$stmt->bind_param("ss", $fromDate, $toDate);
$stmt->execute();
$result_top_products = $stmt->get_result();

$productNames = [];
$productSales = [];

while ($row = $result_top_products->fetch_assoc()) {
    $productNames[] = $row['TENSP'];
    $productSales[] = $row['total_sold'];
}
$stmt->close();

// Thống kê tổng số đơn hàng theo tháng
$sql_total_orders = "SELECT MONTH(NGAYMUA) AS month, COUNT(idHD) AS total_orders 
                     FROM donhang 
                     WHERE NGAYMUA BETWEEN ? AND ?
                     GROUP BY month 
                     ORDER BY month";

$stmt = $conn->prepare($sql_total_orders);
$stmt->bind_param("ss", $fromDate, $toDate);
$stmt->execute();
$result_total_orders = $stmt->get_result();

$totalOrders = [];

while ($row = $result_total_orders->fetch_assoc()) {
    $totalOrders[] = $row['total_orders'];
}
$stmt->close();

$sql_order_status = "SELECT TRANGTHAI, COUNT(idHD) AS total_orders 
                     FROM donhang 
                     WHERE NGAYMUA BETWEEN ? AND ?
                     GROUP BY TRANGTHAI";

$stmt = $conn->prepare($sql_order_status);
$stmt->bind_param("ss", $fromDate, $toDate);
$stmt->execute();
$result_order_status = $stmt->get_result();

// Khởi tạo đếm theo từng nhóm trạng thái
$orderStatusCount = [
    'Thành công' => 0,
    'Hủy bỏ' => 0,
    'Đang xử lý' => 0
];

while ($row = $result_order_status->fetch_assoc()) {
    $status = (int)$row['TRANGTHAI'];
    $count = (int)$row['total_orders'];

    if ($status == 4) {
        $orderStatusCount['Thành công'] += $count;
    } elseif ($status == 5) {
        $orderStatusCount['Hủy bỏ'] += $count;
    } else {
        $orderStatusCount['Đang xử lý'] += $count;
    }
}
$stmt->close();

// Đổ dữ liệu ra cho biểu đồ
$orderStatusLabels = array_keys($orderStatusCount);
$orderStatusData = array_values($orderStatusCount);


// Thống kê tỷ lệ tăng trưởng doanh thu theo tháng
$sql_revenue_growth = "WITH MonthlyRevenue AS (
                        SELECT MONTH(NGAYMUA) AS month, SUM(THANHTIEN) AS revenue 
                        FROM donhang 
                        WHERE NGAYMUA BETWEEN ? AND ?
                        GROUP BY month 
                        ORDER BY month
                       )
                       SELECT 
                           month, 
                           revenue, 
                           LAG(revenue) OVER (ORDER BY month) AS previous_revenue, 
                           (revenue - LAG(revenue) OVER (ORDER BY month)) / LAG(revenue) OVER (ORDER BY month) * 100 AS growth_rate 
                       FROM MonthlyRevenue";

$stmt = $conn->prepare($sql_revenue_growth);
$stmt->bind_param("ss", $fromDate, $toDate);
$stmt->execute();
$result_revenue_growth = $stmt->get_result();

$revenueGrowth = [];

while ($row = $result_revenue_growth->fetch_assoc()) {
    $revenueGrowth[] = $row['growth_rate'];
}
$stmt->close();

// Xuất toàn bộ thành JSON
echo json_encode([
    'months' => $months,
    'revenues' => $revenues,
    'productNames' => $productNames,
    'productSales' => $productSales,
    'totalOrders' => $totalOrders,
    'orderStatusLabels' => $orderStatusLabels,
    'orderStatusData' => $orderStatusData,
    'revenueGrowth' => $revenueGrowth
]);
?>