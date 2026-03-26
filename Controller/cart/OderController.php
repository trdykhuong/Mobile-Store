<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../Model/cart/OderModel.php';

$orderModel = new OrderModel();
$idTK = $_SESSION['id'] ?? 0;

if (isset($_SESSION['voucher_code'])) {
    unset($_SESSION['voucher_code']);
}

// Lấy danh sách sản phẩm đã chọn
$selected_products = $_POST['selected_products'] ?? $_SESSION['selected_products'] ?? [];
$_SESSION['selected_products'] = $selected_products; // Lưu lại vào session

// Lấy giỏ hàng từ session
$cart = $_SESSION['cart'] ?? [];

// Chỉ lấy sản phẩm đã chọn
$selected_cart = [];
foreach ($selected_products as $idCTSP) {
    if (isset($cart[$idCTSP])) {
        $selected_cart[$idCTSP] = $cart[$idCTSP];
    }
}

$hoten = $_SESSION['hoten'] ?? null;
$phone = $_SESSION['sdt'] ?? null;
$email = $_SESSION['email'] ?? null;
// Xử lý đặt hàng
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btDathang']) && !empty($cart)) {
    $name = !empty($hoten) ? $hoten : trim($_POST['name']);
    $phone = !empty($phone) ? $phone : trim($_POST['phone']);
    $email = !empty($email) ? $email : trim($_POST['email']);
    $address = trim($_POST['address']);
    $_SESSION['address'] = $address; // Lưu lại địa chỉ vào session
    $voucher_code = (int)($_POST['voucher_code']);
    $idVC = (int)$_POST['shipping_method'];

    if (empty($name) || empty($phone) || empty($email) || empty($address) || empty($idVC)) {
        echo "Vui lòng nhập đầy đủ thông tin!";
        exit();
    }

    if (empty($idTK) || $idTK == 0) {
        echo "Lỗi: Tài khoản không hợp lệ!";
        exit();
    }

    $cart = $orderModel->getCartItems($idTK);

    if (empty($cart)) {
        $cart = $_SESSION['cart'] ?? [];
    }
    $_SESSION['cart'] = $cart; // Cập nhật session để đồng bộ với DB

    // Tính tổng tiền sản phẩm
    $tongtien = 0;
    foreach ($cart as $item) {
        $gia = (float)$item['GIA'];
        $soluong = (int)$item['SOLUONG'];   
        $discount = (float)($item['DISCOUNT'] ?? 0);
        $tongtien += $soluong * ($gia - ($gia * $discount / 100));
    }

    // Lấy phí vận chuyển
    $phivc = $orderModel->getShippingFee($idVC);
    if (empty($phivc)) $phivc = 0;

    // Lấy giá trị giảm giá
    $giamgia = $orderModel->getVoucherDiscount($voucher_code);

    // Tổng tiền sau khi áp dụng giảm giá và phí vận chuyển
    $tongtien = $tongtien - $giamgia + $phivc;
}

$shippingMethods = $orderModel->getShippingMethods();
$vouchers = $orderModel->getVouchers();
?>