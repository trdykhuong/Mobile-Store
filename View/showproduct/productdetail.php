<?php
include __DIR__ . "/../../Controller/productdetail/productdetail.php";
include_once __DIR__ . "/../../Controller/rating/avg_rating.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$idSP = $sanpham['idSP'] ?? 0;

// Gọi hàm để lấy dữ liệu đánh giá trung bình và số lượng đánh giá
$ratingData = get_AvgRating($db, $idSP);
$avgRating = $ratingData['avgRating'];
$totalReviews = $ratingData['totalReviews'];
$ratingsCount = $ratingData['ratingsCount'];

// Lấy danh sách chi tiết sản phẩm (màu sắc, dung lượng)
$queryDetails = "SELECT * FROM chitietsanpham WHERE idSP = ?";
$stmtDetails = $db->prepare($queryDetails);
$stmtDetails->bind_param("i", $idSP);
$stmtDetails->execute();
$resultDetails = $stmtDetails->get_result();
$productDetails = [];
while ($row = $resultDetails->fetch_assoc()) {
    $productDetails[] = $row;
}
$stmtDetails->close();
// ID NÀY LÀ IDtkhoan
if (isset($_GET['id'])) {
    $idTK = $_GET['id'];
    $db = new Database();
    $db->connect();

    $sql = "SELECT c.idSP, c.SOLUONG, p.TENSP, p.GIA, p.IMG, p.DISCOUNT
            FROM chitietgiohang c
            JOIN sanpham p ON c.idSP = p.idSP
            JOIN chitietsanpham ctsp ON c.idCT = ctsp.idCTSP
            WHERE c.idTK = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $idTK);
    $stmt->execute();
    $result = $stmt->get_result();

    $cart_items = [];

    while ($row = $result->fetch_assoc()) {
        $cart_items[$row['idSP']] = $row;
    }

    $isFavorite = false;

    if (isset($_SESSION['id'])) {
        $idTKhoan = $_SESSION["id"];
        $idSP = isset($sanpham['idSP']) ? (int) $sanpham['idSP'] : 0;

        if ($idTKhoan && $idSP) {
            $stmt1 = $db->prepare("SELECT 1 FROM favourite WHERE idTK = ? AND idSP = ?");
            if (!$stmt1) {
                die("Lỗi SQL: " . $con->error);
            }

            $stmt1->bind_param("ii", $idTKhoan, $idSP);
            $stmt1->execute();
            $stmt1->store_result();

            if ($stmt1->num_rows > 0) {
                $isFavorite = true;
            }
            $stmt1->close();
        }
    }

    $stmt->close();
    $db->close();
}

?>

<title>Chi tiết sản phẩm</title>
<link rel="stylesheet" href="../../css/showproduct/productdetail.css">
<link rel="stylesheet" href="../../css/start.css">

<body>
    <header>
        <div class="header">
            <?php include('../trangchu/header.php'); ?>
        </div>
    </header>

    <main class="main" id="main">
        <div class="container">
            <div class="container-product-detail">
                <?php if (isset($sanpham) && !empty($sanpham)): ?>
                    <div class="nav-product-detail">
                        <div class="product-name">
                            <p>
                                <a href="../trangchu/trangchu.php"><span>Trang chủ</span></a> /
                                <a href="../../View/showproduct/productlist.php"><span>Sản phẩm</span></a> /
                                <span><?= $sanpham['TENSP'] ?></span>
                            </p>
                        </div>
                    </div>

                    <div class="product-detail">
                        <div class="product-detail-img">
                            <div class="form-img">
                                <img id="mainProductImage" src="../../images/products/<?= $sanpham['IMG'] ?>" alt="<?= $sanpham['TENSP'] ?>">
                            </div>

                            <?php if ($sanpham['DISCOUNT'] > 0): ?>
                                <div class="discount-percentage">
                                    <p class="sales-percent">
                                        <?= $sanpham['DISCOUNT'] ?>
                                    </p>
                                    <p class="sales-percent-second">
                                        Off
                                    </p>
                                </div>
                            <?php endif; ?>

                            <div class="love-product">
                                <i id="likeIcon" class="fa <?= $isFavorite ? 'fa-heart' : 'fa-heart-o' ?>"></i>
                            </div>
                            <?php
                            $tongTonKho = 0;
                            foreach ($productDetails as $detail) {
                                $tongTonKho += $detail['TONKHO'];
                            }
                            ?>

                            <?php if ($tongTonKho <= 0): ?>
                                
                            <?php endif; ?>

                            <div class="stock-info-img" id="display_outstock" style="display: none;">
                                    <div class="back-info"></div>
                                    <span>Hết <br> hàng</span>
                                </div>


                            <div class="line"></div>

                            <div class="description-form">
                                <span>Thông tin sản phẩm</span>
                                <div class="description">
                                    <?= $sanpham['MOTA'] ?>
                                </div>
                            </div>
                        </div>

                        <div class="product-detail-price">
                            <div class="name">
                                <?= $sanpham['TENSP'] ?>
                                <!-- (start.css) -->
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

                            <div class="price">
                                <span>Giá bán</span>
                                <div class="price-detail">
                                    <div class="price-detail-left">
                                        <p id="price-sale" class="price-sale">
                                            <?= number_format($sanpham['GIA'] - ($sanpham['GIA'] * $sanpham['DISCOUNT'] / 100), 0, ',', '.') ?>đ
                                        </p>
                                        <p id="price-original" class="price-original">
                                            <?= number_format($sanpham['GIA'], 0, ',', '.') ?>đ
                                        </p>
                                    </div>
                                    
                                    <div id="stock-info" class="stock-info">

                                    </div>
                                </div>
                                <div class="like-and-share">
                                    <div class="share-icon">
                                        <p>
                                            <i class="fa fa-share"></i>Chia sẻ :
                                            <img src="../../images/system/Logo_fb.png" alt="">
                                            <img src="../../images/system/Logo_zalo.png" alt="">
                                            <img src="../../images/system/Logo_tiktok.png" alt="">
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Chọn màu sắc -->
                            <div class="product-options">
                                <?php
                                foreach ($productDetails as $detail) {
                                    $color = $detail['MAUSAC'];
                                    if (!empty($color) && $color !== 'KHÔNG CÓ') {
                                        echo ' <h4>Chọn màu sắc:</h4>';
                                        break;
                                    }
                                }
                                ?>
                               
                                <div id="color-buttons">
                                    <?php
                                    $uniqueColors = [];
                                    foreach ($productDetails as $detail) {
                                        $color = $detail['MAUSAC'];
                                        if (!in_array($color, $uniqueColors) && $color !== 'KHÔNG CÓ') {
                                            $uniqueColors[] = $color;
                                            echo '
                                                <button type="button" id="color_change" class="color-btn" 
                                                data-color="' . htmlspecialchars($color) . '" 
                                                data-img="../../images/products/' . htmlspecialchars($detail['IMG']) . '">
                                                ' . htmlspecialchars($color) . '</button>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Chọn dung lượng -->
                            <?php
                            $hasDungLuong = false;
                            foreach ($productDetails as $detail) {
                                if (!empty($detail['DUNGLUONG'])) {
                                    $hasDungLuong = true;
                                    break;
                                }
                            }

                            if ($hasDungLuong):
                            ?>  
                                <div class="product-options">                                   
                                    <div id="capacity-options">
                                    <h4 style="display: none;" id="name-btn-ram">Chọn dung lượng:</h4>
                                        <?php foreach ($productDetails as $detail): ?>
                                            <button type="button" class="capacity-btn"
                                                data-id="<?= $detail['idCTSP'] ?>"
                                                data-color="<?= htmlspecialchars($detail['MAUSAC']) ?>"
                                                data-capacity="<?= htmlspecialchars($detail['DUNGLUONG']) ?>"
                                                data-price-adjustment="<?= $detail['DIEUCHINHGIA'] ?>"
                                                data-stock="<?= $detail['TONKHO'] ?>"
                                                style="display: none;">
                                                <?= htmlspecialchars($detail['DUNGLUONG']) ?>
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Form ẩn -->
                            <!-- <input type="hidden" name="color" id="selected-color" value="">
                            <input type="hidden" name="capacity" id="selected-capacity" value=""> -->

                            <div class="btn-cart">
                                <!-- Đặt các hidden input dùng chung -->
                                <input type="hidden" id="data-idCTSP" value="<?= $detail['idCTSP'] ?>">
                                <input type="hidden" id="data-stock" value="<?= $detail['TONKHO'] ?>">

                                <form id="muaNgayForm" method="post" action="../cart/cart.php">
                                    <input type="hidden" name="idSP" value="<?= $sanpham['idSP'] ?>">
                                    <input type="hidden" name="idCTSP" id="selected-idCTSP" value="">
                                    <input type="hidden" name="TENSP" value="<?= htmlspecialchars($sanpham['TENSP']) ?>">
                                    <input type="hidden" name="GIA" value="<?= $sanpham['GIA'] ?>">
                                    <input type="hidden" name="IMG" value="<?= $detail['IMG'] ?>">
                                    <input type="hidden" name="DISCOUNT" value="<?= $sanpham['DISCOUNT'] ?>">
                                    <input type="hidden" name="buy_now" value="1">
                                    <input type="hidden" name="color" id="selected-color" value="">
                                    <input type="hidden" name="capacity" id="selected-capacity" value="">
                                    <button type="button" onclick="buyNow()"
                                        class="btn-buy" id="check_outstock">
                                        Mua ngay
                                    </button>
                                </form>

                                <form id="add-to-cart-form" method="post" action="../cart/cart.php">
                                    <input type="hidden" name="idSP" value="<?= $sanpham['idSP'] ?>">
                                    <input type="hidden" name="idCTSP" value="<?= $detail['idCTSP'] ?>">
                                    <input type="hidden" name="TENSP" value="<?= htmlspecialchars($sanpham['TENSP']) ?>">
                                    <input type="hidden" name="GIA" value="<?= $sanpham['GIA'] ?>">
                                    <input type="hidden" name="IMG" value="<?= $detail['IMG'] ?>">
                                    <input type="hidden" name="DISCOUNT" value="<?= $sanpham['DISCOUNT'] ?>">
                                    <input type="hidden" name="add_to_cart" value="1">
                                    <input type="hidden" name="color" id="selected-color" value="<?= $detail['MAUSAC'] ?>">
                                    <input type="hidden" name="capacity" id="selected-capacity" value="<?= $detail['DUNGLUONG'] ?>">
                                    <button type="button" onclick="addToCart()"
                                        class="btn-add" id="cart_outstock">
                                        <i class="fa fa-shopping-cart"></i>
                                        <div><span>Thêm giỏ hàng</span></div>
                                    </button>
                                </form>
                                <!-- Thông báo thành công -->
                                <div id="success-message" class="success-message"></div>

                                <!-- Thông báo lỗi -->
                                <div id="error-message" class="error-message"></div>

                                <!-- Nhập số lượng -->
                                <div id="quantity-modal" class="modal">
                                    <div class="modal-content">
                                        <h3>Nhập số lượng mua hàng</h3>
                                        <input type="number" id="quantity-input" placeholder="Nhập số lượng" min="1" />
                                        <div class="modal-actions">
                                            <button id="cancel-quantity" class="cancel-quantity">Hủy</button>
                                            <button id="confirm-quantity" class="confirm-quantity">Xác nhận</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="note">
                                <p>
                                    <span>Mua máy bạn sẽ được hỗ trợ </span> <br>

                                    ✔️Trả góp 0% qua thẻ tín dụng. <br>

                                    ✔️Trả góp lãi suất thấp qua các công ty Tài chính <br>

                                    ✔️Đặt hàng online – Thanh toán tại nhà <br>

                                    ✔️Thời gian giao hàng nhanh chóng <br>

                                    ➖Nội thành Tp.HCM: Giao nhanh trong 2h <br>

                                    ➖Các tỉnh thành khác: Nhận hàng 3 - 5 ngày. Không tính Thứ 7, Chủ Nhật và các ngày lễ
                                </p>
                            </div>
                        </div>

                        <div class="product-detail-btn">
                            <div class="warranty">
                                <div class="fisrt-warranty">
                                    <i class="fa fa-shield"></i>
                                    <div>
                                        <span>Bảo hành 12 tháng</span>
                                    </div>
                                </div>

                                <div class="line-warranty"></div>

                                <div class="second-warranty">
                                    ✔️Máy mới Fullbox 100% - Chưa Active - Chính Hãng <br>
                                    ✔️Được hỗ trợ 1 đổi 1 trong 30 ngày nếu có lỗi từ nhà sản xuất <br>
                                    ✔️Bảo hành chính hãng 12 tháng
                                </div>
                            </div>

                            <div class="gift">
                                <div class="gift-name">
                                    <i class="fa fa-gift"></i>
                                    <span>Ưu đãi khi mua <?= $sanpham['TENSP'] ?></span>
                                </div>
                                <div class="gift-content">
                                    ✔️Giảm <span>5%</span> tối đa <span>200.000đ</span>, áp dụng kỳ hạn 6/12 tháng với đơn
                                    hàng từ 700.000đ khi thanh toán qua Kredivo <br>

                                    ✔️Giảm <span>5%</span> cho đơn từ <span>53.000đ</span> khi thanh toán qua Home PayLater
                                    (5 lần/tháng) <br>

                                    ✔️Giảm <span>5%</span> cho đơn từ <span>56.000đ</span> khi thanh toán qua Home PayLater
                                    (5 lần/tháng) <br>

                                    ✔️Giảm <span>10%</span> tối đa <span>500.000đ</span>cho khách hàng mới Home PayLater
                                    (Thứ 7 - Chủ Nhật) <br>

                                    ✔️Giảm thêm <span>5</span> - <span>15%</span> cho khách hàng thân thiết khi mua kèm phụ
                                    kiện (Áp dụng một số sản phẩm)
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hộp đánh giá (start.css) -->
                    <div class="boxReview">
                        <div class="review-summary">
                            <div class="title-evaluation">
                                <h3>Đánh Giá & Nhận Xét Về <?= $sanpham['TENSP'] ?></h3>
                            </div>

                            <div class="evaluation-table">
                                <div class="evaluation-quantity">
                                    <div class="review-score">
                                        <span class="score"><?= $avgRating ?>/5</span>
                                        <div class="stars">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="star <?= $i <= round($avgRating) ? 'filled' : '' ?>"><i class="fa fa-star"></i></span>
                                            <?php endfor; ?>
                                        </div>
                                        <p>
                                            <a href="#reviews">
                                                Có <span><?= $totalReviews ?></span> nhận xét và đánh giá
                                            </a>
                                        </p>
                                    </div>

                                    <div class="rating-bars">
                                        <?php foreach ($ratingsCount as $star => $count): ?>
                                            <div class="rating-bar">
                                                <span><?= $star ?> <i class="fa fa-star"></i></span>
                                                <div class="bar">
                                                    <div class="filled-bar" style="width: <?= ($totalReviews > 0) ? ($count / $totalReviews) * 100 : 0 ?>%;"></div>
                                                </div>
                                                <p><?= $count ?> đánh giá</p>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php include 'review.php'; ?>
                    </div>

                <?php else: ?>
                    <p>Không tìm thấy sản phẩm.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <?php include "../trangchu/footer.php" ?>
    </footer>
</body>
<script src="../../js/showproduct/productdetail.js"> </script>
<script>
    // đừng xóa
    const productVariants = <?= json_encode($productDetails) ?>;
    // Xử lý sự kiện click vào trái tim
    document.getElementById('likeIcon').addEventListener('click', function() {
        let icon = this;
        let action = icon.classList.contains('fa-heart-o') ? "add" : "remove";
        fetch('../../inc/handle_fav.php', {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `idSP=<?= $sanpham['idSP'] ?>&action=${action}`
            })
            .then(response => response.text())
            .then(data => {
                if (data === "success") {
                    if (action === "add") {
                        icon.classList.remove('fa-heart-o');
                        icon.classList.add('fa-heart');
                        showSuccess(data.message || "Thêm vào yêu thích thành công!");
                    } else {
                        icon.classList.remove('fa-heart');
                        icon.classList.add('fa-heart-o');
                        showSuccess(data.message || "Đã xóa khỏi yêu thích!");
                    }
                } else {
                    showError(data.message || "Vui lòng đăng nhập trước khi chọn yêu thích!");
                }
            })
            .catch(error => console.error("Lỗi AJAX:", error));
    });

    // js thông báo lỗi
    function showError(message) {
        const errorDiv = document.getElementById("error-message");
        errorDiv.innerText = message;
        errorDiv.style.display = "block";

        setTimeout(() => {
            errorDiv.style.display = "none";
        }, 3000);
    }
    // js thông báo thành công
    function showSuccess(message) {
        const successDiv = document.getElementById("success-message");
        successDiv.innerText = message;
        successDiv.style.display = "block";

        setTimeout(() => {
            successDiv.style.display = "none";
        }, 3000);
    }
    // mau va dung luong
    document.addEventListener('DOMContentLoaded', function() {
        let chooseColor = null;
        let chooseCapacity = null;

        const colorButtons = document.querySelectorAll('.color-btn');
        const capacityButtons = document.querySelectorAll('.capacity-btn');
        const selectedColorInput = document.getElementById('selected-color');
        const selectedCapacityInput = document.getElementById('selected-capacity');
        const mainImage = document.getElementById('mainProductImage');
        const priceSaleElement = document.getElementById('price-sale');
        const priceOriginalElement = document.getElementById('price-original');
        const stockInfoElement = document.getElementById('stock-info');

        const basePrice = <?= $sanpham['GIA'] ?>;
        const discount = <?= $sanpham['DISCOUNT'] ?>;

        // Chọn màu
        colorButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                chooseColor = this.dataset.color;
                selectedColorInput.value = chooseColor;

                colorButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                if (this.dataset.img) {
                    mainImage.src = this.dataset.img;
                }

                // Hiện dung lượng phù hợp
                capacityButtons.forEach(btn => {
                    const capacityValue = btn.dataset.capacity;

                    if (capacityValue === "KHÔNG CÓ") {
                        btn.style.display = 'none'; 
                        document.getElementById("name-btn-ram").style.display= 'none';
                       
                        return;
                    }


                    if (capacityValue !== "KHÔNG CÓ") {
                        document.getElementById("name-btn-ram").style.display= 'block';
                            btn.style.display = (btn.dataset.color === chooseColor) ? 'inline-block' : 'none';
                            btn.classList.remove('active');
                    }
                });

                chooseCapacity = null;
                selectedCapacityInput.value = "";

                // Tự động chọn dung lượng đầu tiên phù hợp
                const first = document.querySelector(`.capacity-btn[data-color="${chooseColor}"]:not([style="display: none"])`);
                if (first) {
                    first.click();
                } else {
                    resetPrice();
                }
            });
        });

        // Chọn dung lượng
        capacityButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                if (this.style.display === 'none') return;

                    chooseCapacity = this.dataset.capacity;
                    selectedCapacityInput.value = chooseCapacity;

                    capacityButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    const priceAdjustment = parseFloat(this.dataset.priceAdjustment);
                    const stock = parseInt(this.dataset.stock);

                updatePrice(priceAdjustment, stock, chooseColor, chooseCapacity);
            });
        });

        // Hàm cập nhật giá
        function updatePrice(priceAdjustment, stock, selectedColor, selectedCapacityId) {
            const newPrice = basePrice + priceAdjustment;
            const discountedPrice = newPrice - (newPrice * discount / 100);

            // Cập nhật hiển thị giá
            priceOriginalElement.textContent = newPrice.toLocaleString('vi-VN') + 'đ';
            priceSaleElement.textContent = discountedPrice.toLocaleString('vi-VN') + 'đ';

            // Cập nhật số lượng tồn kho hoặc hiển thị "Hết hàng"
            if (stock <= 0) {
                stockInfoElement.textContent = 'Hết hàng';
                stockInfoElement.classList.add('out-of-stock'); // Thêm class nếu muốn style riêng
            } else {
                stockInfoElement.textContent = 'SL: ' + stock;
                stockInfoElement.classList.remove('out-of-stock');
            }

            // Cập nhật giá trị vào form
            document.getElementById('selected-color').value = selectedColor;
            document.getElementById('selected-capacity').value = selectedCapacityId;
            var buyButton = document.getElementById("check_outstock");
            var cartButton = document.getElementById("cart_outstock");
            if(stock <= 0) {
                document.getElementById('display_outstock').style.display = "inline-block";
                buyButton.disabled = true;
                buyButton.classList.add("disabled");
                cartButton.disabled = true;
                cartButton.classList.add("disabled");
            } else {
                document.getElementById('display_outstock').style.display = "none";
                buyButton.disabled = false;
                buyButton.classList.remove("disabled");
                cartButton.disabled = false;
                cartButton.classList.remove("disabled");
            }
        }

        // Hàm reset giá về mặc định
        function resetPrice() {
            priceOriginalElement.textContent = basePrice.toLocaleString('vi-VN') + 'đ';
            const discountedPrice = basePrice - (basePrice * discount / 100);
            priceSaleElement.textContent = discountedPrice.toLocaleString('vi-VN') + 'đ';

            const defaultStock = stockInfoElement.dataset.defaultStock;
            stockInfoElement.textContent = 'SL: ' + defaultStock;
        }
        // Tự động chọn màu đầu tiên khi trang tải
        // if (colorButtons.length > 0) {
        //     colorButtons[0].click();
        // }
    });
    // cập nhật màu và dung lượng sau mỗi lần nhấn
    // Khi người dùng click chọn màu
    document.querySelectorAll(".color-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            const color = btn.getAttribute("data-color");
            document.getElementById("selected-color").value = color;
        });
    });

    // Khi người dùng click chọn dung lượng
    document.querySelectorAll(".capacity-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            const capacity = btn.getAttribute("data-capacity");
            document.getElementById("selected-capacity").value = capacity;
        });
    });

    // Tự động chọn màu đầu tiên khi trang tải
    // if (colorButtons.length > 0) {
    //     colorButtons[0].click();
    // }
</script>