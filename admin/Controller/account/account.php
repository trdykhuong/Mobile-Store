<?php
    function getAllAccounts() {
        $connect = new mysqli("localhost:3306", "root", "", "chdidong");

        if ($connect->connect_error) {
            die("Kết nối thất bại: " . $connect->connect_error);
        }

        $sql = "SELECT tk.idTK, tk.USERNAME, tk.HOTEN, tk.EMAIL, q.TENQUYEN, tk.TRANGTHAI 
                FROM taikhoan tk
                LEFT JOIN quyen q ON tk.idQUYEN = q.idQUYEN WHERE q.idQUYEN <> 0";

        $result = $connect->query($sql);

        $accounts = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $accounts[] = $row;
            }
        }

        $connect->close();
        return $accounts;
    }
?>