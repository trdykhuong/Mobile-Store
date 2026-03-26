<?php

$conn = mysqli_connect("localhost:3306", "root", "", "chdidong");

if (isset($_GET["chon"])) {
    if($_GET["chon"] == "Add"){
    $path = $_SERVER["DOCUMENT_ROOT"] . '/HTTTDN/admin/View/Provider/AddProviderView.php';
    include("$path");
}}

if(isset($_POST["Add-NCC"])){
    if (!empty($_POST["txtTenNcc"])
    &&!empty($_POST["txtSDT"])
    &&!empty($_POST["txtDiachi"])
    ){

    $tenncc = $_POST["txtTenNcc"];
    $sdt = $_POST["txtSDT"];
    $diachi = $_POST["txtDiachi"];

    //Tính id của hiện tại của nhà cung cấp
    $id_temp = mysqli_query($conn, "SELECT idNCC FROM nhacungcap");
    $num_rows_id = mysqli_num_rows($id_temp) + 1;

    // Kiểm tra xem nhà cung cấp đã tồn tại trong cơ sở dữ liệu chưa
    $sql_check_provider = mysqli_query($conn, "SELECT * FROM nhacungcap WHERE TENNCC = '$tenNCC'");
    $num_rows = mysqli_num_rows($sql_check_provider);

    if ($num_rows > 0) {
        echo '<script>alert("Nhà cung cấp đã tồn tại")</script>';
    } else {
        // Nhà cung cấp chưa tồn tại, thêm Nhà cung cấp mới
        $sqp_insert_provider = mysqli_query($conn, "INSERT INTO nhacungcap
        (idNCC, TENNCC, SDT, DIACHI) 
        VALUES('$num_rows_id', '$tenncc', '$sdt', '$diachi')");

        echo '<script>alert("Thêm ' .$tenncc. ' thành công")</script>';
        header('Location: index.php?page=provider&chon=list');
    }
    }

    //Reload page
    echo "<meta http-equiv='refresh' content='0'>";
}   

mysqli_close($conn);
?>