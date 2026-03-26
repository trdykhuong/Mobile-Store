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
        $cart = [];
        $stmt = $this->db->prepare("SELECT g.idSP, ctsp.idCTSP, ctsp.MAUSAC, ctsp.DUNGLUONG, g.SOLUONG, s.TENSP, s.GIA, s.IMG, s.DISCOUNT
            FROM chitietgiohang g 
            JOIN sanpham s ON g.idSP = s.idSP
            JOIN chitietsanpham ctsp ON g.idSP = ctsp.idSP 
            WHERE g.idTK = ?");
        $stmt->bind_param("i", $idTK);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $cart[$row['idSP']] = [
                'TENSP'   => $row['TENSP'],
                'GIA'     => $row['GIA'],
                'IMG'     => $row['IMG'],
                'soluong' => $row['SOLUONG'],
                'DISCOUNT' => $row['DISCOUNT']
            ];
        }
        $stmt->close();
        return $cart;
    }
    // Lấy thông tin vận chuyển
    public function getShippingMethods() {
        $sql = "SELECT idVC, TENDVVC, GIAVANCHUYEN FROM dvvanchuyen WHERE TRANGTHAI = 1";
        return $this->db->query($sql);
    }

    public function getVouchers() {
        $sql = "SELECT MAKHUYENMAI, CODE, GIATRI 
                FROM khuyenmai 
                WHERE SOLUONG > 1 AND TRANGTHAI = 1 AND HANSUDUNG >= CURDATE() AND NGAYAPDUNG <= CURDATE()";
        return $this->db->query($sql);
    }

    public function getShippingFee($idVC) {
        $stmt = $this->db->prepare("SELECT GIAVANCHUYEN FROM dvvanchuyen WHERE idVC = ? AND TRANGTHAI = 1");
        $stmt->bind_param("i", $idVC);
        $stmt->execute();
        $stmt->bind_result($phivc);
        $stmt->fetch();
        $stmt->close();
        return $phivc;
    }

    public function getVoucherDiscount($MA) {
        $stmt = $this->db->prepare("SELECT GIATRI FROM khuyenmai WHERE MAKHUYENMAI = ?");
        $stmt->bind_param("i", $MA);
        $stmt->execute();
        $stmt->bind_result($giamgia);
        $stmt->fetch();
        $stmt->close();
        return $giamgia;
    }
}
?>