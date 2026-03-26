
<title>Hoàn Thành Đơn Hàng</title>
<link rel="stylesheet" href="../../css/cart/thankyou.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<body>
    <header>
        <div class="header">
            <?php include('../trangchu/header.php'); ?>
        </div>
    </header>

    <main class="main" id="main">
        <div class="container">
            <div class="content">

                <!-- Thanh điều hướng  -->
                <div class="nav-thankyou-detail">
                    <div class="nav-thankyou-name">
                        <p>
                            <a href="../trangchu/trangchu.php"><span>Trang chủ</span></a> / Đơn hàng của bạn / Hoàn thành đơn hàng
                        </p>
                    </div>
                </div>

                <!-- Thanh quá trình đặt hàng -->
                <div class="cart-process">
                    <div class="cart-process-detail-1">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <div class="cart-process-detail-name">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Kiểm tra <br> giỏ hàng</span>
                        </div>
                    </div>
                    <div class="cart-process-line-1"></div>
                    <div class="cart-process-detail-2">
                        <i class="fa-solid fa-user"></i>
                        <div class="cart-process-detail-name">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Thông tin <br> mua hàng</span>
                        </div>
                    </div>
                    <div class="cart-process-line-2"></div>
                    <div class="cart-process-detail-3">
                        <i class="fa-solid fa-box"></i>
                        <div class="cart-process-detail-name">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Phương thức <br> thanh toán</span>
                        </div>
                    </div>
                    <div class="cart-process-line-3"></div>
                    <div class="cart-process-detail-4">
                        <i class="fa-solid fa-circle-check"></i>
                        <div class="cart-process-detail-name">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Đặt hàng <br> thành công</span>
                        </div>
                    </div>
                </div>

                <!-- Cảm ơn -->
                <div class="thankyou-form">
                    <div class="content-thankyou">
                        <h4>Hoàn tất!<br>My Pow chân thành cảm ơn bạn đã sử dụng dịch vụ!</h4>
                        <!-- Nút Tiếp tục mua sắm -->
                        <div class="continue-shopping">
                            <a href="../trangchu/trangchu.php" class="btn-continue-shopping">Tiếp tục mua sắm</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include('../trangchu/footer.php'); ?>
</body>