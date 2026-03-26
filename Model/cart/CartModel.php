<?php
require_once '../../Model/db_connect.php';

class CartModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->db->connect();
    }

    public function getProductById($idSP)
    {
        $stmt = $this->db->prepare("SELECT * FROM sanpham WHERE idSP = ?");
        $stmt->bind_param("i", $idSP);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();
        return $product;
    }

    // Lấy idCTSP theo idSP, MAUSAC và DUNGLUONG
    public function getIdCTSPByIdSP($idSP, $mausac, $dungluong)
    {
        $stmt = $this->db->prepare("SELECT idCTSP FROM chitietsanpham WHERE idSP = ? AND MAUSAC = ? AND DUNGLUONG = ?");
        $stmt->bind_param("iss", $idSP, $mausac, $dungluong);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['idCTSP'] ?? 0;
    }
    // Lấy số lượng sản phẩm trong kho
    public function getStockQuantity($idCTSP)
    {
        $stmt = $this->db->prepare("SELECT TONKHO FROM chitietsanpham WHERE idCTSP = ?");
        $stmt->bind_param("i", $idCTSP);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['TONKHO'] ?? 0;
    }

    // Đếm số lượng sản phẩm trong giỏ hàng
    public function getCartQuantity($idTK)
    {
        $stmt = $this->db->prepare("SELECT COUNT(idCT) AS COUNT FROM chitietgiohang WHERE idTK = ?");
        $stmt->bind_param("i", $idTK);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['COUNT'] ?? 0;
    }

    // Thêm sản phẩm vào giỏ hàng
    public function addToCart($idTK, $idSP, $idCTSP, $soluong, $mausac, $dungluong) {
        $stmt = $this->db->prepare("SELECT SOLUONG FROM chitietgiohang WHERE idTK = ? AND idCT = ?");
        $stmt->bind_param("ii", $idTK, $idCTSP);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    
        if ($row = $result->fetch_assoc()) {
            $new_qty = $row['SOLUONG'] + $soluong;
            $stmt = $this->db->prepare("UPDATE chitietgiohang SET SOLUONG = ? WHERE idTK = ? AND idSP = ? AND idCT = ?");
            $stmt->bind_param("iiii", $new_qty, $idTK, $idSP, $idCTSP);
        } else {
            $stmt = $this->db->prepare("INSERT INTO chitietgiohang (idTK, idSP, idCT, SOLUONG, MAUSAC, DUNGLUONG) 
                                            VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiiss", $idTK, $idSP, $idCTSP, $soluong, $mausac, $dungluong);
        }
    
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    
    // Xóa sản phẩm khỏi giỏ hàng
    public function removeProduct($idTK, $idCT)
    {
        $stmt = $this->db->prepare("DELETE FROM chitietgiohang WHERE idTK = ? AND idCT = ?");
        $stmt->bind_param("ii", $idTK, $idCT);
        $stmt->execute();
        $stmt->close();
    }
    // Xóa toàn bộ giỏ hàng
    public function clearCart($idTK)
    {
        $stmt = $this->db->prepare("DELETE FROM chitietgiohang WHERE idTK = ?");
        $stmt->bind_param("i", $idTK);
        $stmt->execute();
        $stmt->close();
    }
    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function updateCart($idTK, $idSP, $idCT, $soluong)
    {
        $stmt = $this->db->prepare("UPDATE chitietgiohang SET SOLUONG = ? WHERE idTK = ? AND idSP = ? AND idCT = ?");
        $stmt->bind_param("iiii", $soluong, $idTK, $idSP, $idCT);
        $stmt->execute();
        $stmt->close();
    }

    // Lấy danh sách sản phẩm người dùng chọn mua qua trang oder_info
    public function getCartItems($idTK)
    {
        $stmt = $this->db->prepare("SELECT d.idCTSP, c.idSP, s.TENSP, s.GIA, d.IMG, s.DISCOUNT, c.SOLUONG, d.TONKHO as STOCK, 
                                            d.MAUSAC, d.DUNGLUONG, d.TONKHO, d.DIEUCHINHGIA 
                                    FROM chitietgiohang c 
                                    JOIN sanpham s ON c.idSP = s.idSP
                                    JOIN chitietsanpham d ON c.idCT = d.idCTSP 
                                    WHERE c.idTK = ? ORDER BY c.THOIGIANTHEM DESC");
        $stmt->bind_param("i", $idTK);
        $stmt->execute();
        $result = $stmt->get_result();
        $cartItems = [];
        while ($row = $result->fetch_assoc()) {
            $cartItems[$row['idCTSP']] = $row;
        }
        $stmt->close();
        return $cartItems;
    }
    // Lấy số lượng sản phẩm hiện tại trong giỏ hàng để cộng dồn
    public function getCartItemQuantity($idTK, $idCTSP)
    {
        $stmt = $this->db->prepare("SELECT SOLUONG FROM chitietgiohang WHERE idTK = ? AND idCT = ?");
        $stmt->bind_param("ii", $idTK, $idCTSP);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['SOLUONG'] ?? 0;
    }

    // public function getCartItemsWithDetails($idTK) {
    //     $stmt = $this->db->prepare("
    //         SELECT c.idSP, s.TENSP, s.GIA, s.IMG, s.DISCOUNT, c.SOLUONG, 
    //                d.mau_sac, d.dung_luong, d.ton_kho
    //         FROM chitietgiohang c
    //         JOIN sanpham s ON c.idSP = s.idSP
    //         JOIN chitietsanpham d ON c.idSP = d.id_san_pham
    //         WHERE c.idTK = ?
    //         ORDER BY c.THOIGIANTHEM DESC
    //     ");
    //     $stmt->bind_param("i", $idTK);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     $cartItems = [];
    //     while ($row = $result->fetch_assoc()) {
    //         $cartItems[$row['idSP']] = $row;
    //     }
    //     $stmt->close();
    //     return $cartItems;
    // }
}
