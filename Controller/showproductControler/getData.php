<?php
include_once __DIR__ . "/../../Model/db_connect.php";

$db = new Database;
$db->connect();

// Lấy danh mục sản phẩm (chỉ lấy các danh mục có id = 1, 5, 8) để hiện trên giao diện
$sql_danhmuc = "SELECT * FROM danhmuc WHERE idDM IN (1, 5, 8)";
$list_danhmuc = $db->getAllDataBySql($sql_danhmuc);

// Lấy danh sách sản phẩm theo từng danh mục + Đánh giá
$sanpham_theo_danhmuc = [];
foreach ($list_danhmuc as $danhmuc) {
    $sql_sanpham = "SELECT * FROM sanpham WHERE TRANGTHAI = 1 AND idDM = " . $danhmuc['idDM'];
    $sanpham_list = $db->getAllDataBySql($sql_sanpham);

    foreach ($sanpham_list as $key => $item) {
        // Truy vấn số sao trung bình và tổng đánh giá
        $query = "SELECT COUNT(*) as total, AVG(rating) as avg_rating FROM rating WHERE idSP = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $item['idSP']);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        // Lưu số sao và tổng đánh giá vào sản phẩm
        $sanpham_list[$key]['totalReviews'] = $result['total'] ?? 0;
        $sanpham_list[$key]['avgRating'] = ($result['avg_rating'] !== null) ? round($result['avg_rating'], 1) : 0;
    }

    $sanpham_theo_danhmuc[$danhmuc['idDM']] = $sanpham_list;
}
?>
