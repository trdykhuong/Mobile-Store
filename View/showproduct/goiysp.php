<?php
$connect = new mysqli("localhost", "root", "", "chdidong");

if (isset($_GET['keyword'])) {
    $keyword = trim($_GET['keyword']);
    $query = "SELECT idSP, TENSP, GIA, IMG FROM sanpham WHERE TENSP LIKE ?";
    $stmt = $connect->prepare($query);
    $param = "%$keyword%";
    $stmt->bind_param("s", $param);
    $stmt->execute();
    $result = $stmt->get_result();

    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }

    echo json_encode($suggestions);
    $stmt->close();
}
?>
