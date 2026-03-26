<title>Thông tin cá nhân</title>
<link rel="stylesheet" href="../../css/user/user-info.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<body>
    <header>
        <div class="header">
            <?php include('../../View/trangchu/header.php'); ?>
        </div>
    </header>

    <main class="main" id="main">
        <?php
        $idAcc = $_SESSION["id"];
        $sql = "SELECT tk.HOTEN, tk.SDT, tk.EMAIL, dh.DIACHI, COUNT(tk.HOTEN) AS SOLUONG, SUM(dh.THANHTIEN) AS TONGTIEN
                    FROM taikhoan tk
                    JOIN donhang dh ON tk.idTK = dh.idTK
                    WHERE tk.idTK = '$idAcc'";

        $TK = mysqli_query($connect, $sql);
        while ($row = mysqli_fetch_array($TK)) {
            $hoten = $row["HOTEN"];
            $sdt = $row["SDT"];
            $email = $row["EMAIL"];
            $diachi = $row["DIACHI"];
        }
        //////////////////////////////////////////////////////////////////////////////////
        $sql1 = "SELECT COUNT(tk.HOTEN) AS SOLUONG, SUM(dh.THANHTIEN) AS TONGTIEN
            FROM taikhoan tk
            JOIN donhang dh ON tk.idTK = dh.idTK
            WHERE tk.idTK = '$idAcc' and dh.TRANGTHAI = 4";

        $TK1 = mysqli_query($connect, $sql1);
        while ($row = mysqli_fetch_array($TK1)) {
            $soluong = $row['SOLUONG'];
            $tongtien = $row['TONGTIEN'];
        }
        ?>
        <div class="container">
            <div class="container-user-info">
                <!-- Thanh điều hướng -->
                <div class="nav-user-info">
                    <div class="nav-user-info-detail">
                        <p>
                            <a href="../../View/trangchu/trangchu.php"><span>Trang chủ</span></a> / Thông tin cá nhân / <span class="operation"></span>
                        </p>
                    </div>
                </div>

                <!-- Nội dung -->
                <div class="user-info-content">
                    <!-- Nội dung trái -->
                    <div class="user-info-content-left">
                        <ul class="list-of-operations">
                            <li class="btn-home">
                                <i class="fas fa-home"></i> Trang chủ
                            </li>
                            <li class="btn-history">
                                <i class="fas fa-shopping-cart"></i> Lịch sử mua hàng
                            </li>
                            <li class="btn-favorite">
                                <i class="fas fa-heart"></i> Yêu thích
                            </li>
                            <li>
                                <a href="../../View/login-register/logout.php">
                                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Nội dung phải -->
                    <div class="user-info-content-right">
                        <!-- Trang chủ -->
                        <div class="user-info-home">
                            <h1>Thông tin của bạn</h1>
                            <div class="home-detail">
                                <div class="name">
                                    <i class="fas fa-user"></i>
                                    <h2><?php echo $_SESSION["username"] ?></h2>
                                </div>

                                <div class="quantity-money-total">
                                    <div class="quantity-total">
                                        <i class="fas fa-box"></i>
                                        <div class="quantity-total-detail">
                                            <p>Số đơn hàng thành công</p>
                                            <span>
                                                <?php echo $soluong ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="money-total">
                                        <i class="fas fa-credit-card"></i>
                                        <div class="money-total-detail">
                                            <p>Tổng chi tiêu</p>
                                            <span>
                                                <?php echo number_format($tongtien ?? 0, 0, ',', '.') ?>đ
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="table">
                                    <table>
                                        <tr>
                                            <td>
                                                <p>Họ tên</p>
                                                <p><?php echo $hoten ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>Số điện thoại</p>
                                                <p><?php echo $sdt ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>Email</p>
                                                <p><?php echo $email ?></p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Trang sản phẩm yêu thích -->
                        <div class="favorite-products">
                            <?php include('../../View/user/user-favorite-products.php'); ?>
                        </div>

                        <!-- Trang lịch sử mua hàng -->
                        <div class="purchase-history">
                            <?php include('../../View/user/user-purchase-history.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <?php include('../../View/trangchu/footer.php') ?>
    </footer>
</body>
<script src="../../js/user/user-info.js"></script>

<script>