<link rel="stylesheet" href="../../css/start.css">
<link rel="stylesheet" href="../../css/showproduct/productlist.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<body>
    <header>
        <div class="header">
            <?php include "../trangchu/header.php" ?>
        </div>
    </header>

    <main class="main" id="main" >
        <input type="hidden" id='userDefine' value="<?= isset($_SESSION['username']) ? 'true' : 'false' ?>">
        <input type="hidden" id="DM" value="<?= isset($_GET['DM'])? $_GET['DM'] : 0 ?>">
        <script src="../../js/showproduct/productlist.js" src="dm_async.js" async></script>
        <div class="container">
            <div class="container-center">
                <!-- Thanh nav -->
                <div class="navigation">
                    <div class="navigation-name">
                        <p>
                            <a href="../trangchu/trangchu.php"><span>Trang chủ</span></a> / 
                            <a href="../../View/showproduct/productlist.php">Sản phẩm</a>
                        </p>
                    </div>
                </div>

                <!-- Nội dung chính -->
                <div class="main-content">
                    <!-- Bộ lọc -->
                    <div class="filter">
                        <div class="filter-content">
                            <div class="filter-content-first">
                                <!-- Nút mở form lọc -->
                                <button class="btn-filter" onclick="OpenFilter(this)">
                                    <i class="fa fa-filter"></i> Lọc
                                </button>

                                <!-- Lọc theo hãng -->
                                <div class="filter-brand">
                                    <div class="filter-brand-choice" id="filter-brand-choice"></div>
                                </div>
                            </div>
                            
                            <!-- Lọc sắp xếp -->
                            <div class="filter-content-second">
                                <p>Sắp xếp theo:</p>
                                <div class="filter-merge">
                                    <span onclick="orderClick(this)" class="order-option">Bán chạy</span>
                                    <span onclick="orderClick(this)" class="order-option">Giảm giá</span>
                                    <span onclick="orderClick(this)" class="order-option">Mới nhất</span>
                                    <span class="merge-price order-option">Giá <i class="fa fa-chevron-down chevron-down"></i></span>
                                </div>
                            </div>

                            <div class="filter-merge-price">
                                <span onclick="orderClick(this)">Giá thấp - cao</span>
                                <span onclick="orderClick(this)">Giá cao - thấp</span>
                            </div>
                        </div>
                        
                        <!-- Form lọc -->
                        <div class="form-btn-filter" id="form-btn-filter">
                            <div class="form-filter">
                                <div class="triangle"></div>
                                <div class="form-filter-content">
                                    <!-- Lọc theo giá -->
                                    <div class="form-filter-price">
                                        <p>Giá</p>
                                        <div class="price-options">
                                            <div class="price-range">
                                                <input type="text" id="price-min" placeholder="Giá thấp nhất" oninput="formatVND(this)">
                                                <div class="price-line"></div>
                                                <input type="text" id="price-max" placeholder="Giá cao nhất" oninput="formatVND(this)">
                                            </div>

                                            <div class="price-range-slider">
                                                <span id='duoi2' onclick="filter_gia(this.id)" class="duoi2">Dưới 2 triệu</span>
                                                <span id='2to10' onclick="filter_gia(this.id)" class="2to10">Từ 2 - 10 triệu</span>
                                                <span id='10to20' onclick="filter_gia(this.id)" class="10to20">Từ 10 - 20 triệu</span>
                                                <span id='tren20' onclick="filter_gia(this.id)" class="tren20">Trên 20 triệu</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Lọc theo hãng -->
                                    <div class="form-filter-brands">
                                        <p>Hãng</p>
                                        <div class="brands-choice" id="brands-choice"></div>
                                    </div>

                                    <!-- Lọc theo danh mục -->
                                    <div class="form-filter-category">
                                        <p>Danh mục</p>
                                        <div class="category-choice" id="category-choice"></div>
                                    </div>

                                    <!-- Nút lọc -->
                                    <div class="form-filter-btn">
                                        <button class="btn-filter-cancel" onclick="closeFilter()">Hủy</button>  
                                        <button class="btn-filter-apply" onclick="Filter()">Áp dụng</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <!-- <input type="number" name="" id="number-product" value=""> -->

                        <!-- <select name="" id="number-product">
                            <option value="0">Tất cả</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                        </select> -->
                    </div>
                    <!-- Danh sách sản phẩm -->
                    <div class="product-display-all">
                        <div class="product-grid" id="product-grid"></div>
                    </div>

                    <!-- Pagination Links -->
                    <div class="pagination">
                        <ul id="paginationUl"></ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <footer>
        <?php include "../trangchu/footer.php" ?>   
    </footer>
</body>

