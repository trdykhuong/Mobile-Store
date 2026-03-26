<?php
    include_once __DIR__ . "/../../Controller/rating/avg_rating.php";
?>

<link rel="stylesheet" href="../../css/start.css">
<link rel="stylesheet" href="../../css/showproduct/productlist.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<body>
    <header>
        <div class="header">
            <?php include('../trangchu/header.php'); ?>
        </div>
    </header>

    <main class="main" id="main">
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

                <div class="main-content">
                    <?php
                        // Kết nối đến cơ sở dữ liệu
                        $connect = new mysqli("localhost", "root", "", "chdidong");

                        // Kiểm tra kết nối
                        if ($connect->connect_error) {
                            die("Kết nối thất bại: " . $connect->connect_error);
                        }

                        // Lấy từ khóa tìm kiếm từ URL
                        if (isset($_GET['keyword'])) {
                            $keyword = trim($_GET['keyword']);
                            $query = "
                            SELECT sp.*, ct.DIEUCHINHGIA
                            FROM sanpham sp
                            JOIN (
                                SELECT idSP, MIN(idCTSP) AS idCTSP
                                FROM chitietsanpham
                                GROUP BY idSP
                            ) AS ctsp ON sp.idSP = ctsp.idSP
                            JOIN chitietsanpham ct ON ct.idCTSP = ctsp.idCTSP
                            WHERE sp.TENSP LIKE ? AND sp.TRANGTHAI = 1";
                            
                            $stmt = $connect->prepare($query);
                            $param = "%$keyword%";
                            $stmt->bind_param("s", $param);
                            $stmt->execute();
                            $result = $stmt->get_result();
                        ?>
                            <div class="product-display-all">
                                <h2>Kết quả tìm kiếm</h2>
                                <?php if ($result->num_rows > 0): ?>
                                    <div class="product-grid">
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <?php
                                                $ratingData = get_AvgRating($db, $row['idSP']);
                                                $avgRating = $ratingData['avgRating'];
                                                $totalReviews = $ratingData['totalReviews'];
                                            ?>
                                            <div class="product-item">
                                                <a href="../../View/showproduct/productdetail.php?id=<?= $row['idSP'] ?>">
                                                    <div class="product-img">
                                                        <img src="../../images/products/<?= $row['IMG'] ?>" alt="<?= $row['TENSP'] ?>">
                                                        <div class="installment">
                                                            <img src="../../images/system/tragop.jpg" alt="Trả góp">
                                                        </div>
                                                        <?php if ($item['DISCOUNT'] > 0): ?>
                                                            <div class="discount-percentage">
                                                                <p class="percent">
                                                                    -
                                                                    <p class="sales-percent">
                                                                        <?=$item['DISCOUNT']?>
                                                                    </p>
                                                                    %
                                                                </p> 
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div class="name">
                                                        <?= $row['TENSP'] ?>
                                                    </div>

                                                    <div class="product-price">
                                                        <!-- Hiển thị đánh giá sao -->
                                                        <div class="rating-index">
                                                            <p>
                                                                <i class="fa fa-heart-o likeIcon" id="likeIcon"></i> Yêu thích
                                                            </p>
                                                            <?php if ($totalReviews > 0 && $avgRating > 0): ?>
                                                                <div class="stars">
                                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                        <span class="star <?= $i <= round($avgRating) ? 'filled' : '' ?>">
                                                                            <i class="fa fa-star"></i>
                                                                        </span>
                                                                    <?php endfor; ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        
                                                        <div class="product-price-sales">
                                                            <div class="price"><?= number_format(($row['GIA'] + $row['DIEUCHINHGIA']) - (($row['GIA'] + $row['DIEUCHINHGIA']) * ($row['DISCOUNT'] ?? 0) / 100), 0, ',', '.') ?>
                                                            </div>
                                                            <div class="price-sales"><?=number_format($item['GIA'], 0, ',','.')?>đ</div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                <?php else: ?>
                                    <p>Không tìm thấy sản phẩm nào.</p>
                                <?php endif; ?>
                            </div>
                        <?php
                            $stmt->close();
                        }

                        // Đóng kết nối CSDL
                        $connect->close();
                    ?>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <?php include('../trangchu/footer.php'); ?>
    </footer>
</body>