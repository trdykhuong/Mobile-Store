<?php

$cart = $_SESSION['cart'] ?? [];
$selected_products = $_SESSION['selected_products'] ?? [];

// Chỉ lấy sản phẩm đã chọn
$selected_cart = [];
foreach ($selected_products as $idCTSP) {
    if (isset($cart[$idCTSP])) {
        $selected_cart[$idCTSP] = $cart[$idCTSP];
    }
}
//  Lưu lại giỏ hàng đã chọn vào session
$_SESSION['selected_cart'] = $selected_cart;

class PaymentController
{
    public function vnPay($totalAmount)
    {
        echo "Thanh toán qua VNPay với số tiền: " . number_format($totalAmount) . " VND";
        $this->createPayment($totalAmount);
    }

    public function createPayment($totalAmount)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_HashSecret = "BEZLUPOPOTXTDYZHCBGDJBHFJPBLSARL"; //Secret key
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost:3000/View/cart/check_vnpay.php";
        $vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
        $apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";

        $startTime = date("YmdHis");
        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

        $vnp_TxnRef = rand(10000, 99999); // Mã giao dịch thanh toán
        $vnp_Amount = $totalAmount * 100; // Nhân 100 vì VNPay tính theo đơn vị nhỏ nhất
        $vnp_Locale = "vn"; // Ngôn ngữ thanh toán mặc định
        $vnp_BankCode = ""; // Không chọn ngân hàng mặc định
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR']; // Địa chỉ IP khách hàng
        $vnp_TmnCode = "0S7T01T8";  // Nhập đúng mã Terminal ID

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => "Thanh toán GD: " . $vnp_TxnRef,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $expire
        );

        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            $hashdata .= ($hashdata ? '&' : '') . urlencode($key) . "=" . urlencode($value);
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $query = rtrim($query, '&'); // Xóa dấu & cuối cùng

        $vnp_Url = $vnp_Url . "?" . $query;
        if ($vnp_HashSecret) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;
        }

        // Save necessary data to session
        $_SESSION['vnp_TxnRef'] = $vnp_TxnRef;
        $_SESSION['vnp_Amount'] = $vnp_Amount;
        $_SESSION['vnp_ExpireDate'] = $expire;

        echo '<script>alert("Chuyển hướng đến VNPay... ' . $vnp_Url . '");debugger;</script>';

        header('Location: ' . $vnp_Url);
        exit();
    }

    public function handleVnpayReturn()
    {
        $vnp_TxnRef = $_SESSION['vnp_TxnRef'] ?? null;
        $vnp_Amount = $_SESSION['vnp_Amount'] ?? null;
        $vnp_TransactionStatus = $_GET['vnp_TransactionStatus'] ?? null;
        $vnp_ResponseCode = $_GET['vnp_ResponseCode'] ?? null;

        if ($vnp_TransactionStatus == '00' && $vnp_ResponseCode == '00') {
            $this->saveOrder($vnp_TxnRef, $vnp_Amount);
            header('Location: http://localhost:3000/View/cart/thankyou.php');
            exit();
        } else {
            unset($_SESSION['vnp_TxnRef'], $_SESSION['vnp_Amount'], $_SESSION['vnp_ExpireDate']);
            $errorMessage = "Thanh toán thất bại! Vui lòng thử lại.";
            if ($vnp_ResponseCode == '24') {
                $errorMessage = "Giao dịch bị hủy bỏ.";
            } elseif ($vnp_ResponseCode == '02') {
                $errorMessage = "Giao dịch không thành công.";
            }
            echo '<script>alert("' . $errorMessage . '"); window.location.href = "http://localhost:3000/View/cart/cart.php";</script>';
            exit();
        }
    }
    public function saveOrder($vnp_TxnRef, $vnp_Amount)
    {
        $connect = new mysqli("localhost", "root", "", "chdidong");
        if ($connect->connect_error) {
            die("Lỗi kết nối: " . $connect->connect_error);
        }
        $selected_cart = $_SESSION['selected_cart'] ?? [];
        $selected_products = $_SESSION['selected_products'] ?? [];
        $address = $_SESSION['address'] ?? '';
        $idVC = $_SESSION['shipping_method'] ?? null;
        $trangthai = 1;
        $idThanhtoan = 2;
        $idTK = $_SESSION['id'] ?? null;
        $discount_code = $_SESSION['voucher_code'] ?? null;
        $sdt = $_SESSION['sdt'] ?? null;

        if ($discount_code){
        $stmt = $connect->prepare("UPDATE khuyenmai SET SOLUONG = SOLUONG - 1 WHERE MAKHUYENMAI = ?");
        $stmt->bind_param("i", $discount_code);
        $stmt->execute();
        $stmt->close();
        }
        // GIẢM SỐ LƯỢNG SẢN PHẨM
        foreach ($selected_cart as $idCTSP => $item) {
            $stmt = $connect->prepare("UPDATE chitietsanpham SET TONKHO = TONKHO - ? WHERE idCTSP = ?");
            $stmt->bind_param("ii", $item['SOLUONG'], $idCTSP);
            $stmt->execute();
            $stmt->close();
        }
        $trangthai = 2;
        $ngaymua = date('Y-m-d');
        $vnp_Amount = $vnp_Amount / 100;
        $stmt = $connect->prepare("INSERT INTO donhang (THANHTIEN, NGAYMUA, DIACHI, MAKHUYENMAI, idVC, TRANGTHAI, idTHANHTOAN, SDT_DH, idTK) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("dssiiiisi", $vnp_Amount, $ngaymua, $address, $discount_code, $idVC, $trangthai, $idThanhtoan, $sdt, $idTK);

        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;
            $stmt->close();

            foreach ($selected_cart as $idCTSP => $item) {
                $stmt = $connect->prepare("INSERT INTO chitiethoadon (idHD, idCTSP, SOLUONG) VALUES (?, ?, ?)");
                $stmt->bind_param("iii", $order_id, $idCTSP, $item['SOLUONG']);
                $stmt->execute();
                $stmt->close();
            }

            // Xóa các sản phẩm đã đặt 
            foreach ($selected_products as $idCTSP) {
                $stmt = $connect->prepare("DELETE FROM chitietgiohang WHERE idTK = ? AND idCT = ?");
                $stmt->bind_param("ii", $idTK, $idCTSP);
                $stmt->execute();
                $stmt->close();
            }

            // Cập nhật lại giỏ hàng trong session
            foreach ($selected_products as $idCTSP) {
                unset($_SESSION['cart'][$idCTSP]);
            }
        } else {
            die("Lỗi khi tạo đơn hàng: " . $connect->error);
        }
    }
}

// Ngân hàng	NCB
// Số thẻ	9704198526191432198
// Tên chủ thẻ	NGUYEN VAN A
// Ngày phát hành	07/15
// Mật khẩu OTP	123456