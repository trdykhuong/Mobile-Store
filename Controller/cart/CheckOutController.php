<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../Model/cart/CheckOutModel.php';

$orderModel = new OrderModel();
$idTK = $_SESSION['id'] ?? 0;
// Lấy thông tin sản phẩm, khuyến mãi, phí vận chuyển, phương thức thanh toán
$cartItems = $orderModel->getCartItems($idTK);
$vouchers = $orderModel->getVouchers();
$shippingMethods = $orderModel->getShippingMethods();
$paymentMethods = $orderModel->getPaymentMethods();

$selected_products = $_POST['selected_products'] ?? $_SESSION['selected_products'] ?? [];
$_SESSION['selected_products'] = $selected_products;
// Lấy danh sách sản phẩm đã chọn
$selected_cart = getSelectedProducts($selected_products, $cartItems);
// Lấy thông tin khách hàng từ session
$hoten = $_SESSION['hoten'] ?? null;
$phone = $_SESSION['sdt'] ?? null;
$email = $_SESSION['email'] ?? null;

$name = $_POST['name'] ?? $hoten;
$phone = $_POST['phone'] ?? $phone;
$email = $_POST['email'] ?? $email;
$address = $_POST['address'] ?? $_SESSION['address'] ?? null;
$idVC = $_POST['shipping_method'] ?? $_SESSION['shipping_method'] ?? 0;
$discount_code = $_POST['voucher_code'] ?? $_SESSION['voucher_code'] ?? null;
$idThanhtoan = $_SESSION['payment_method'] ?? 0;

$_SESSION['hoten'] = $name;
$_SESSION['sdt'] = $phone;
$_SESSION['email'] = $email;
$_SESSION['address'] = $address;
$_SESSION['shipping_method'] = $idVC;
$_SESSION['voucher_code'] = $discount_code;
// Lấy thông tin vận chuyển
$shippingInfo = $orderModel->getShippingInfo($idVC);
$tenDVVC = $shippingInfo['tenDVVC'] ?? "Không xác định";
$phivc = $shippingInfo['phivc'] ?? 0;
// Lấy thông tin khuyến mãi
$discount = $orderModel->getDiscount($discount_code);
$giamgia = $discount['giamgia'] ?? 0;
$code = $discount['code'] ?? "Không áp dụng";
// Tính tổng tiền sản phẩm
$tongtien = array_reduce($selected_cart, function ($sum, $item) {
    return $sum + ((int)$item['SOLUONG'] * $item['GIA'] + $item['DIEUCHINHGIA'] - (($item['GIA'] + $item['DIEUCHINHGIA']) * ($item['DISCOUNT'] ?? 0) / 100));
}, 0);

$tongtien = $tongtien - $giamgia + $phivc;
// Xác nhận đặt hàng
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirmOrder'])) {
    if (empty($selected_cart)) {
        die("Giỏ hàng trống!");
    }

    $idThanhtoan = (int)$_POST['payment_method'];
    $_SESSION['payment_method'] = $idThanhtoan;
    // Thanh toán khi nhận hàng, lưu db
    if ($idThanhtoan == 1) {
        $orderModel->saveOrder($tongtien, $address, $discount_code, $idVC, $idThanhtoan, $idTK, $selected_cart, $selected_products);
        header("Location: thankyou.php");
        exit();
    // Thanh toán online, chuyển hướng qua cổng thanh toán Vnpay
    } else if ($idThanhtoan == 2) {
        require_once("create_vnpay.php");
        $payment = new PaymentController();
        $payment->createPayment($tongtien);
        exit();
    }
}
// Lấy danh sách sản phẩm đã chọn
function getSelectedProducts($selected_products, $cartItems)
{
    $selected_cart = [];
    foreach ($selected_products as $idSP) {
        if (isset($cartItems[$idSP])) {
            $selected_cart[$idSP] = $cartItems[$idSP];
        }
    }
    return $selected_cart;
}

?>