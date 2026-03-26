<?php
    $connect = new mysqli("localhost:3306" , "root" , "", "chdidong");
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); 
    }
?>

<!-- TK admin: 
0123456789
123qwe

TK khách:
0706433641
Tdk123 -->

<title>Trang chủ</title>
<link rel="stylesheet" href="../../css/trangchu/trangchu.css">
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

<body>
    <header>
        <div class="header">
            <?php include('header.php') ?>
        </div>
    </header>

    <main class="main" id="main">
        <div class="container">
            <!-- Banner -->
            <div class="banner">
                <!-- Banner_1 -->
                <div class="big-banner">
                    <div class="slider">
                        <div class="slide">
                            <img src="../../images/system/banner-1.jpg" alt="" class="img-huge" />
                        </div>
                        <div class="slide">
                            <img src="../../images/system/banner-2.jpg" alt="" class="img-huge" />
                        </div>
                        <div class="slide">
                            <img src="../../images/system/banner-3.jpg" alt="" class="img-huge" />
                        </div>
                        <div class="slide">
                            <img src="../../images/system/banner-4.jpg" alt="" class="img-huge" />
                        </div>
                        <div class="slide">
                            <img src="../../images/system/banner-5.jpg" alt="" class="img-huge" />
                        </div>
                        <div class="slide">
                            <img src="../../images/system/banner-6.jpg" alt="" class="img-huge" />
                        </div>
                        <div class="slide">
                            <img src="../../images/system/banner-7.jpg" alt="" class="img-huge" />
                        </div>
                        <div class="slide">
                            <img src="../../images/system/banner-8.jpg" alt="" class="img-huge" />
                        </div>

                        <button class="btn-left hide-on-mobile">
                            <i class="fa fa-arrow-left"></i>
                        </button>
                        <button class="btn-right hide-on-mobile">
                            <i class="fa fa-arrow-right"></i>
                        </button>

                        <div class="dots"></div>
                    </div>
                </div>

                <!-- Banner_2  -->
                <div class="small-banner">
                    <div class="small-slider">
                        <div class="small-slide">
                            <img src="../../images/system/small-banner-1.jpg" alt="" class="small-img-huge" />
                            <img src="../../images/system/small-banner-2.jpg" alt="" class="small-img-huge" />
                        </div>
                        <div class="small-slide">
                            <img src="../../images/system/small-banner-3.jpg" alt="" class="small-img-huge" />
                            <img src="../../images/system/small-banner-4.jpg" alt="" class="small-img-huge" />
                        </div>
                        <div class="small-slide">
                            <img src="../../images/system/small-banner-5.jpg" alt="" class="small-img-huge" />
                            <img src="../../images/system/small-banner-6.jpg" alt="" class="small-img-huge" />
                        </div>
                        <div class="small-slide">
                            <img src="../../images/system/small-banner-7.jpg" alt="" class="small-img-huge" />
                            <img src="../../images/system/small-banner-8.jpg" alt="" class="small-img-huge" />
                        </div>
                        <div class="small-slide">
                            <img src="../../images/system/small-banner-9.jpg" alt="" class="small-img-huge" />
                            <img src="../../images/system/small-banner-10.jpg" alt="" class="small-img-huge" />
                        </div>
                        <div class="small-slide">
                            <img src="../../images/system/small-banner-11.jpg" alt="" class="small-img-huge" />
                            <img src="../../images/system/small-banner-12.jpg" alt="" class="small-img-huge" />
                        </div>

                        <button class="small-btn-left">
                            <i class="fa fa-arrow-left"></i>
                        </button>
                        <button class="small-btn-right">
                            <i class="fa fa-arrow-right"></i>
                        </button>

                        <div class="small-dots"></div>
                    </div>
                </div>
            </div>

            <div class="recommended-products">
                <?php
                    include "../../View/showproduct/showproduct.php";
                ?>
            </div>  
        </div>

        <div class="back-to-top">
            <i class="fa fa-arrow-up"></i>
        </div>
    </main>
     
    <footer>
        <?php include('footer.php') ?>   
    </footer>

    <?php if(isset($_SESSION['sucess']) && $_SESSION['sucess'] == "success"): ?>
        <div id="success-message" class="success-message">
            Đăng nhập thành công
        </div>
        <?php unset($_SESSION['sucess']); ?>
    <?php endif; ?>
</body>

<script src="../../js/trangchu/trangchu.js"></script>