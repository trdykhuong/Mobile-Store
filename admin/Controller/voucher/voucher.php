<?php
include_once(__DIR__ . "/../../../Model/db_connect.php");


class VoucherModel {

    public function getAllVouchers() {
        $db = new Database();
        $db->connect();

        $sql = "SELECT * FROM khuyenmai";
        $result = $db->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getVoucherById($id) {
        $db = new Database();
        $db->connect();

        $sql = "SELECT * FROM khuyenmai WHERE MAKHUYENMAI = '$id'";
        $result = $db->query($sql);
        return $result->fetch_assoc();
    }

    public function addVoucher($code, $giatri, $soluong, $ngayapdung, $hansudung, $trangthai) {
        $db = new Database();
        $db->connect();

        $sql = "INSERT INTO khuyenmai (CODE, GIATRI, SOLUONG, NGAYAPDUNG, HANSUDUNG, TRANGTHAI) 
                VALUES ('$code', '$giatri', '$soluong', '$ngayapdung', '$hansudung', '$trangthai')";
        return $db->query($sql);
    }

    public function updateVoucher($id, $code, $giatri, $soluong, $ngayapdung, $hansudung, $trangthai) {
        $db = new Database();
        $db->connect();

        $sql = "UPDATE khuyenmai SET CODE='$code', GIATRI='$giatri', SOLUONG='$soluong', 
                NGAYAPDUNG='$ngayapdung', HANSUDUNG='$hansudung', TRANGTHAI='$trangthai'
                WHERE MAKHUYENMAI='$id'";
        return $db->query($sql);
    }

    public function deleteVoucher($id) {
        $db = new Database();
        $db->connect();

        $sql = "DELETE FROM khuyenmai WHERE MAKHUYENMAI='$id'";
        return $db->query($sql);
    }
}
?>
