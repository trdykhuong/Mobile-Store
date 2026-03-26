<?php
function getAllEmployee() {
    $connect = new mysqli("localhost:3306", "root", "", "chdidong");

    if ($connect->connect_error) {
        die("Kết nối thất bại: " . $connect->connect_error);
    }

    $sql = "SELECT *
            FROM nhanvien nv, taikhoan tk, quyen q
            WHERE nv.idTK = tk.idTK AND tk.idQUYEN = q.idQUYEN AND tk.idQUYEN <> 1 AND tk.idQUYEN <> 0";

    $result = $connect->query($sql);

    $employee = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $employee[] = $row;
        }
    }

    $connect->close();
    return $employee;
}
?>