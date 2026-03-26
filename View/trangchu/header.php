<?php
    $connect = mysqli_connect("localhost", "root", "", "chdidong");

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include __DIR__ . "/../../Controller/showproductControler/getData.php";

    $user = isset($_SESSION['username']) ? $_SESSION['username'] : NULL;
?>

<title>Cửa hàng di động</title>
<link rel="stylesheet" href="../../css/trangchu/header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'> </script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

<body>
    <header>
        <div class="header">
            <div class="header-logo">
                <img src="../../images/system/logo copy.png" alt="">
                <h1>
                    <a href="../../View/trangchu/trangchu.php">
                        MyPow
                    </a>
                </h1>
            </div>

            <div class="header-right">
                <div class="header-search">
                    <form action="../../View/showproduct/timkiem.php" method="GET">
                        <input type="text" id="search-box" name="keyword" placeholder="Bạn cần tìm gì?" required onkeyup="fetchSuggestions()">
                        <button type="submit" class="btn-search">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                    <div id="suggestions-box" class="suggestions"></div>
                </div>
            </div>

            <div class="header-hotline">
                <i class="fa fa-mobile"></i>
                <div class="phone">
                    <p>
                        Gọi mua hàng <br>
                        <span>1900 1789</span>
                    </p>
                </div>
            </div>

            <a href="../../View/cart/cart.php" class="to-cart">
                <div class="header-cart">
                    <i class="fa fa-shopping-cart"></i>
                    <div class="header-cart-message">
                        <span id="cart-quantity">0</span>
                    </div>
                    <div>
                        <span class="header-cart-info">Giỏ hàng</span>
                    </div>
                </div>
            </a>

            <?php output_Name() ?>
        </div>
        
        <div class="nav-product">
            <ul>
                 <li>
                    <a href="../../View/showproduct/productlist.php">
                        <img src="../../images/system/product-view.png" alt="">
                        <span>Sản phẩm</span>
                    </a>
                    <div class="nav-line"></div>
                </li>
                <li>
                    <a href="../../View/showproduct/productlist.php?DM=1">
                        <img src="../../images/system/smartphone.png" alt="" class="img-nav">
                        <span>Điện thoại</span>
                    </a>
                </li>
                <li>
                    <a href="../../View/showproduct/productlist.php?DM=8">
                        <img src="../../images/system//ipad.png" alt="" class="img-nav">
                        <span>iPad</span>
                    </a>
                </li>
                <li>
                    <a href="../../View/showproduct/productlist.php?DM=7">
                        <img src="../../images/system/tablet.png" alt="" class="img-nav">
                        <span>Tablet</span>
                    </a>
                </li>
                <li>
                    <a href="../../View/showproduct/productlist.php?DM=6">
                        <img src="../..//images/system/smart-watch.png" alt="" class="img-nav">
                        <span>Đồng hồ</span>
                    </a>
                </li>
                <li>
                    <a href="../../View/showproduct/productlist.php?DM=5">
                        <img src="../../images/system/headphone.png" alt="" class="img-nav">
                        <span>Tai nghe</span>
                    </a>
                </li>
                <li>
                    <a href="../../View/showproduct/productlist.php?DM=3">
                        <img src="../../images/system/adapter.png" alt="" class="img-nav">
                        <span>Dây sạc</span>
                    </a>
                </li>
                <li>
                    <a href="../../View/showproduct/productlist.php?DM=2">
                        <img src="../../images/system/adapter.png" alt="" class="img-nav">
                        <span>Adaptor</span>
                    </a>
                </li>
                <li>
                    <a href="../../View/showproduct/productlist.php?DM=4">
                        <img src="../../images/system/adapter.png" alt="" class="img-nav">
                        <span>Ốp lưng</span>
                    </a>
                </li>
            </ul>
        </div>
    </header>
</body>

<?php
    function output_Name()
    {
        if (isset($_SESSION["username"])) {
            echo
                '<a href="../../View/user/user-info.php" class="header-btn-login">  
                    <div class="header-user">
                        <i class="fa fa-user"></i>
                        <div class="header-user-info">
                            <p>Tài khoản</p>
                            <p id="username">' . $_SESSION["username"] . '</p>
                        </div>
                    </div>
                </a>';
        } else {
            echo 
                '<a href="../../View/login-register/dangnhap.php?page=login" class="header-btn-login">  
                    <div class="header-user">
                        <i class="fa fa-user"></i>
                        <div class="header-user-info">
                            <p>Tài khoản</p>
                            Đăng nhập
                        </div>
                    </div>
                </a>';    
        }
    }
?>

<script>
    const usernameElement = document.getElementById('username');
    const username = usernameElement.textContent;

    if (username.length > 10) {
        usernameElement.textContent = username.substring(0, 8) + '...';
    }

    function updateCartQuantity() {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "../../Controller/cart/getCartQuantity.php", true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                document.getElementById("cart-quantity").textContent = xhr.responseText;
            }
        };
        xhr.send();
    }

    document.addEventListener("DOMContentLoaded", updateCartQuantity);
    
    setInterval(updateCartQuantity, 1000); // Cập nhật mỗi giây
</script>

<script src="../../js/trangchu/goi-y-sp.js"></script>