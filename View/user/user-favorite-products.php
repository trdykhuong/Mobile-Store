<?php
    include_once __DIR__ . "/../../Controller/rating/avg_rating.php";
    require_once "../../Model/db_connect.php";
    require_once "../../Model/Client/user_model.php";

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $db_Con = new Database();
    $db_Con->connect();

    $idTKK = $_SESSION["id"];

    $favProducts = get_FavProducts($db_Con, $idTKK); 
?>

<link rel="stylesheet" href="../../css/user/user-favorite-products.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<body>
    <h1>Sản phẩm yêu thích</h1>
    <div class="favorite-products-list">
        <table id="product-table">
            <thead>
                <tr>
                    <td>
                        <div class="title-content">
                            <div class="left-content">
                                <input type="checkbox" id="select-all">
                                <p>Danh sách sản phẩm</p>
                            </div>
                            <div class="right-content">
                                <i class="fas fa-heart-crack" id="remove-favorites"></i>
                            </div>
                        </div>
                    </td>
                </tr>
            </thead>

            <tbody>
                <?php 
                    if (!empty($favProducts)) {
                    foreach ($favProducts as $product) {
                    // Gọi hàm tính số sao trung bình
                    $ratingData = get_AvgRating($db_Con, $product['idSP']);
                    $avgRating = $ratingData['avgRating']; // Lấy trung bình sao
                ?>
                <tr class="product-row" data-id="<?= $product['idSP'] ?>">
                    <td>
                        <div class="products">
                            <div class="first-td">
                                <div class="product-img">
                                    <input type="checkbox" class="product-checkbox">
                                    <img src="../../images/products/<?= $product['IMG']  ?>" alt="">
                                </div>
                                <p>
                                    <?= $product['TENSP'] ?>
                                </p>
                            </div>

                            <div class="second-td">
                                <p>
                                    Giá: 
                                    <span><?= number_format( $product['GIA'], 0, ',', '.') ?></span>đ
                                </p>

                                <?php if ($avgRating > 0): ?>
                                    <div class="rate">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fa-solid fa-star" style="color: <?= ($i <= round($avgRating)) ? '#F59E0B' : '#ccc' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                <?php endif; ?>
                                <i class="fas fa-heart"></i>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php
                }
                }
                ?>
            </tbody>
        </table>

        <!-- Điều khiển phân trang -->
        <div class="pagination">
            <button id="prev-btn" disabled>
                <i class="fas fa-arrow-left"></i>
            </button>
            <div id="page-numbers"></div>
            <button id="next-btn">
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>

    <div id="notification" class="notification"></div>
</body>
<script src="../../js/user/user-favorite-products.js"></script>