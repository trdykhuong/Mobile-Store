<?php
require_once '../../Model/db_connect.php';

class OrderModel {
    private $db;

    public function __construct() {
        $this->db = new mysqli("localhost", "root", "", "chdidong");
        if ($this->db->connect_error) {
            die("Lỗi kết nối: " . $this->db->connect_error);
        }
    }
    // Lấy thông tin giỏ hàng
    public function getCartItems($idTK) {
        $stmt = $this->db->prepare("SELECT g.idCT, g.SOLUONG, s.TENSP, s.GIA, ct.IMG, s.DISCOUNT, ct.MAUSAC, ct.DUNGLUONG, ct.DIEUCHINHGIA 
            FROM chitietgiohang g 
            JOIN chitietsanpham ct ON g.idCT = ct.idCTSP
            JOIN sanpham s ON ct.idSP = s.idSP 
            WHERE g.idTK = ?");
        $stmt->bind_param("i", $idTK);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart = [];
        while ($row = $result->fetch_assoc()) {
            $cart[$row['idCT']] = $row; // key là idCTSP
        }
        $stmt->close();
        return $cart;
    }
    
    // Lấy thông tin khuyến mãi
    public function getVouchers() {
        $sql = "SELECT MAKHUYENMAI, CODE, GIATRI 
                FROM khuyenmai 
                WHERE SOLUONG > 1 AND TRANGTHAI = 1 AND HANSUDUNG >= CURDATE() AND NGAYAPDUNG <= CURDATE()";
        return $this->db->query($sql);
    }
    // Lấy thông tin vận chuyển
    public function getShippingMethods() {
        $sql = "SELECT idVC, TENDVVC, GIAVANCHUYEN FROM dvvanchuyen WHERE TRANGTHAI = 1";
        return $this->db->query($sql);
    }
    // Lấy thông tin phương thức thanh toán
    public function getPaymentMethods() {
        $sql = "SELECT idTHANHTOAN, TENPHUONGTHUC FROM ptthanhtoan";
        return $this->db->query($sql);
    }
    // Lấy thông tin giảm giá của tưng sp
    public function getDiscount($discount_code) {
        $stmt = $this->db->prepare("SELECT CODE, GIATRI FROM khuyenmai WHERE MAKHUYENMAI = ?");
        $stmt->bind_param("i", $discount_code);
        $stmt->execute();
        $stmt->bind_result($code, $giamgia);
        $stmt->fetch();
        $stmt->close();
        return ['code' => $code, 'giamgia' => $giamgia];
    }
    // Lấy thông tin vận chuyển
    public function getShippingInfo($idVC) {
        $stmt = $this->db->prepare("SELECT TENDVVC, GIAVANCHUYEN FROM dvvanchuyen WHERE idVC = ?");
        $stmt->bind_param("i", $idVC);
        $stmt->execute();
        $stmt->bind_result($tenDVVC, $phivc);
        $stmt->fetch();
        $stmt->close();
        return ['tenDVVC' => $tenDVVC, 'phivc' => $phivc];
    }
    // Lấy pt thanh toán
    public function getPaymentMethodName($idThanhtoan) {
        $stmt = $this->db->prepare("SELECT TENPHUONGTHUC FROM ptthanhtoan WHERE idTHANHTOAN = ?");
        $stmt->bind_param("i", $idThanhtoan);
        $stmt->execute();
        $stmt->bind_result($tenPT);
        $stmt->fetch();
        $stmt->close();
        return $tenPT;
    }
    // Lưu đơn hàng
    public function saveOrder($tongtien, $address, $discount_code, $idVC, $idThanhtoan, $idTK, $selected_cart, $selected_products) {
        // giảm số lượng MÃ KHUYẾN MÃ
        $stmt = $this->db->prepare("UPDATE khuyenmai SET SOLUONG = SOLUONG - 1 WHERE MAKHUYENMAI = ?");
        $stmt->bind_param("i", $discount_code);
        $stmt->execute();
        $stmt->close();
        // GIẢM SỐ LƯỢNG SẢN PHẨM
        foreach ($selected_cart as $idSP => $item) {
            $stmt = $this->db->prepare("UPDATE chitietsanpham SET TONKHO = TONKHO - ? WHERE idCTSP = ?");
            $stmt->bind_param("ii", $item['SOLUONG'], $idSP);
            $stmt->execute();
            $stmt->close();
        }
        // Nếu đơn thanh toán khi nhận hàng thì trạng thái đơn hàng là 1
        $trangthai = 1;
        $ngaymua = date('Y-m-d');
        $sdt = $_SESSION['sdt'] ?? null;
        $stmt = $this->db->prepare("INSERT INTO donhang (THANHTIEN, NGAYMUA, DIACHI, MAKHUYENMAI, idVC, TRANGTHAI, idTHANHTOAN, SDT_DH, idTK) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("dssiiiisi", $tongtien, $ngaymua, $address, $discount_code, $idVC, $trangthai, $idThanhtoan, $sdt, $idTK);
        
        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;
            $stmt->close();
            // Thêm chi tiết đơn hàng
            foreach ($selected_cart as $idCTSP => $item) {
                $stmt = $this->db->prepare("INSERT INTO chitiethoadon (idHD, idCTSP, SOLUONG) VALUES (?, ?, ?)");
                $stmt->bind_param("iii", $order_id, $idCTSP, $item['SOLUONG']);
                $stmt->execute();
                $stmt->close();
            }

            // Xóa các sản phẩm đã đặt 
            foreach ($selected_products as $idCTSP) {
                $stmt = $this->db->prepare("DELETE FROM chitietgiohang WHERE idTK = ? AND idCT = ?");
                $stmt->bind_param("ii", $idTK, $idCTSP);
                $stmt->execute();
                $stmt->close();
            }

            // Cập nhật lại giỏ hàng trong session
            foreach ($selected_products as $idCTSP) {
                unset($_SESSION['cart'][$idCTSP]);
            }
        } else {
            die("Lỗi khi tạo đơn hàng: " . $this->db->error);
        }
    }
}
?>