<?php
session_start();
include_once __DIR__ . "/../../Model/db_connect.php";

if (!isset($_SESSION['username'])) {
    echo "Lỗi: Chưa đăng nhập!";
    exit();
}

// Kết nối database
$db = new Database();
$db->connect();

$idTK = $_SESSION['id'] ?? 0;

// Nhận dữ liệu JSON từ request
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['idCTSP']) || !isset($data['rating'])) {
    echo "Lỗi: Dữ liệu không hợp lệ!";
    exit();
}


$idCTSP = intval($data['idCTSP']);
$rating = intval($data['rating']);
$comment = isset($data['comment']) ? htmlspecialchars(trim($data['comment']), ENT_QUOTES, 'UTF-8') : "";

// Kiểm tra điểm đánh giá hợp lệ
if ($rating < 1 || $rating > 5) {
    echo "Lỗi: Điểm đánh giá không hợp lệ!";
    exit();
}
$idSP = '';
// Kiểm tra xem người dùng đã mua sản phẩm này chưa và đơn hàng đã hoàn thành chưa
$check_purchase_query = "
    SELECT ctsp.idSP 
    FROM chitiethoadon ch
    JOIN donhang dh ON ch.idHD = dh.idHD
    JOIN chitietsanpham ctsp ON ch.idCTSP = ctsp.idCTSP
    WHERE dh.idTK = ? AND ch.idCTSP = ? AND dh.TRANGTHAI = 4"; // Chỉ đơn hàng có trạng thái 'Giao hàng thành công' (idSTATUS = 4)
$check_purchase_stmt = $db->prepare($check_purchase_query);
$check_purchase_stmt->bind_param("ii", $idTK, $idCTSP);
$check_purchase_stmt->execute();
$check_purchase_stmt->store_result();

if ($check_purchase_stmt->num_rows === 0) {
    echo " Bạn chỉ có thể đánh giá sản phẩm sau khi đã nhận hàng!";
    $check_purchase_stmt->close(); // Đóng statement ngay tại đây
    exit();
}

$getidSP = "SELECT idSP FROM chitietsanpham WHERE idCTSP = ?";
$getidSP_stmt = $db->prepare($getidSP);
$getidSP_stmt = $db->prepare($getidSP);
$getidSP_stmt->bind_param("i", $idCTSP);
$getidSP_stmt->execute();

// Lấy kết quả từ câu truy vấn
$result = $getidSP_stmt->get_result();

if ($result->num_rows > 0) {
    // Lấy hàng đầu tiên (nếu có nhiều hàng, bạn có thể dùng vòng lặp while)
    $row = $result->fetch_assoc();
    $idSP = $row['idSP'];
} else {
    echo "Không tìm thấy sản phẩm với idCTSP = " . $idCTSP;
}

// Đóng statement
$getidSP_stmt->close();


// Kiểm tra xem người dùng đã đánh giá sản phẩm này chưa
$check_query = "SELECT id FROM rating WHERE idSP = ? AND idTK = ?";
$check_stmt = $db->prepare($check_query);
$check_stmt->bind_param("ii", $idSP, $idTK);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    // Nếu đã có đánh giá, cập nhật lại
    $query = "UPDATE rating SET rating = ?, comment = ?, created_at = NOW() WHERE idSP = ? AND idTK = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("isii", $rating, $comment, $idSP, $idTK);
} else {
    // Nếu chưa có, thêm mới
    $query = "INSERT INTO rating (idSP, idTK, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $db->prepare($query);
    $stmt->bind_param("iiis", $idSP, $idTK, $rating, $comment);
}

if ($stmt->execute()) {
    echo "success";
} else {
    echo "Lỗi SQL: " . $stmt->error;
    file_put_contents("debug.log", date("[Y-m-d H:i:s]") . " Lỗi SQL: " . $stmt->error . "\n", FILE_APPEND);
}

// Đóng kết nối
$stmt->close();
$check_stmt->close();
$check_purchase_stmt->close();
$db->close();
?>
