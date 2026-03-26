<?php
    include __DIR__ . "/../../Controller/showproductControler/getData.php";

    $isFavoriteList = [];
    if (isset($_SESSION['id'])) {
        $idTK = $_SESSION['id'];
        $db = new Database();
        $db->connect();

        $stmt = $db->prepare("SELECT idSP FROM favourite WHERE idTK = ?");
        $stmt->bind_param("i", $idTK);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $isFavoriteList[$row['idSP']] = true;
        }

        $stmt->close();
        $db->close();
    }
?>

<link rel="stylesheet" href="../../css/start.css">
<link rel="stylesheet" href="../../css/showproduct/showproduct.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div class="product-display">
    <?php foreach ($list_danhmuc as $danhmuc): ?>
        <div class="category">
            <h2 id="name-to-point-<?= $danhmuc['idDM'] ?>"><?= $danhmuc['LOAISP'] ?></h2>
            <div id="custom-content-<?= $danhmuc['idDM'] ?>"></div>
            <?php
            $result = $sanpham_theo_danhmuc[$danhmuc['idDM']] ?? [];
            ?>
            <?php if (!empty($result) && is_array($result)): ?>
                <div class="product-list">
                    <?php foreach ($result as $item): ?>
                        <div class="product">
                            <a href="../../View/showproduct/productdetail.php?id=<?= $item['idSP'] ?>">
                                <div class="product-img">
                                    <img src="../../images/products/<?= $item['IMG'] ?>" alt="<?= $item['TENSP'] ?>">
                                    <div class="installment">
                                        <img src="../../images/system/tragop.jpg" alt="">
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
                                    <?= $item['TENSP'] ?>
                                </div>
                            </a>

                            <div class="product-price">
                                <!-- Hiển thị đánh giá sao -->
                                <div class="rating-index">
                                    <div class="love-product">
                                        <p>
                                            <i class="fa <?= isset($isFavoriteList[$item['idSP']]) ? 'fa-heart' : 'fa-heart-o' ?>" data-idsp="<?= $item['idSP'] ?>"></i> Yêu thích                                        
                                        </p>
                                    </div>
                                    <?php if ($item['totalReviews'] > 0): ?> <!-- Chỉ hiển thị nếu có đánh giá -->
                                        <div class="stars">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="star <?= ($i <= round($item['avgRating'])) ? 'filled' : '' ?>"><i
                                                        class="fa fa-star"></i></span>
                                            <?php endfor; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="product-price-sales">
                                    <div class="price">
                                        <?= number_format($item['GIA'] - ($item['GIA'] * $item['DISCOUNT'] / 100), 0, ',', '.') ?></div>
                                    <div class="price-sales"><?= number_format($item['GIA'], 0, ',', '.') ?>đ</div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Không có sản phẩm nào.</p>
            <?php endif; ?>

            <div class="btn-view-all">
                <?php if (trim($danhmuc['LOAISP']) === "Điện thoại"): ?>
                    <a href="../../View/showproduct/productlist.php?DM=1">
                        <button>
                            <span>Xem toàn bộ sản phẩm</span>
                            <i class="fa fa-angle-right"></i>
                        </button>
                    </a>
                <?php endif ?>
                <?php if (trim($danhmuc['LOAISP']) === "Tai Nghe"): ?>
                    <a href="../../View/showproduct/productlist.php?DM=5">
                        <button>
                            <span>Xem toàn bộ sản phẩm</span>
                            <i class="fa fa-angle-right"></i>
                        </button>
                    </a>
                <?php endif ?>
                <?php if (trim($danhmuc['LOAISP']) === "iPad"): ?>
                    <a href="../../View/showproduct/productlist.php?DM=8">
                        <button>
                            <span>Xem toàn bộ sản phẩm</span>
                            <i class="fa fa-angle-right"></i>
                        </button>
                    </a>
                <?php endif ?>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Thông báo thành công -->
    <div id="success-message" class="success-message"></div>

    <!-- Thông báo lỗi -->
    <div id="error-message" class="error-message"></div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let danhMucs = <?= json_encode($list_danhmuc) ?>;

        danhMucs.forEach(function (danhmuc) {
            let nameElement = document.getElementById("name-to-point-" + danhmuc.idDM);
            let customContent = document.getElementById("custom-content-" + danhmuc.idDM);

            if (nameElement && nameElement.textContent.trim() === "Điện thoại") {
                nameElement.style.display = "none";

                let newContent = document.createElement("div");
                newContent.innerHTML = `
                <div class="checkpoint-product">
                    <!--<a href="/View/showproduct/productlist.php?idHang=1">-->
                        <div class="checkpoint-product-img">
                            <img src="../../images/system/iphone.jpg" alt="iPhone">
                            <div class="name-check">
                                <span>iPhone</span>
                            </div>
                        </div>
                    </a>
                    
                    <!--<a href="../../View/showproduct/productlist.php?idHang=2">-->
                        <div class="checkpoint-product-img">
                            <img src="../../images/system/samsung.jpg" alt="Samsung">
                            <div class="name-check">
                                <span>Samsung</span>
                            </div>
                        </div>
                    </a>

                    <!--<a href="../../View/showproduct/productlist.php?idHang=3">-->
                        <div class="checkpoint-product-img">
                            <img src="../../images/system/xiaomi.jpg" alt="Xiaomi" style="padding: 5px;">
                            <div class="name-check">
                                <span>Xiaomi</span>
                            </div>
                        </div>
                    </a>
                </div>
                `;
                customContent.appendChild(newContent);
            }

            if (nameElement && nameElement.textContent.trim() === "Tai Nghe") {
                nameElement.style.display = "none";

                let newContent = document.createElement("div");
                newContent.innerHTML = `
                <div class="checkpoint-product">
                    <div class="checkpoint-product-img">
                        <img src="../../images/system/airpods.jpg" alt="">
                        <div class="name-check">
                            <span>Airpods</span>
                        </div>
                    </div>
                    <div class="checkpoint-product-img">
                        <img src="../../images/system/tainghe.jpg" alt="">
                        <div class="name-check">
                            <span>Tai nghe</span>
                        </div>
                    </div>
                    <div class="checkpoint-product-img">
                        <img src="../../images/products/Tai nghe In-Ear Headphones Basic.jpg" alt="" style="padding: 5px;">
                        <div class="name-check">
                            <span>In-Ear</span>
                        </div>
                    </div>
                </div>
                `;
                customContent.appendChild(newContent);
            }

            if (nameElement && nameElement.textContent.trim() === "iPad") {
                nameElement.style.display = "none";

                let newContent = document.createElement("div");
                newContent.innerHTML = `
                <div class="checkpoint-product">
                    <div class="checkpoint-product-img">
                        <img src="../../images/products/iPad mini 7 2024 5G 128GB.jpg" alt="">
                        <div class="name-check">
                            <span>iPad mini</span>
                        </div>
                    </div>
                    <div class="checkpoint-product-img">
                        <img src="../../images/products/iPad Gen 9 Wifi 64GB.jpg" alt="" style="padding: 5px;">
                        <div class="name-check">
                            <span>iPad Gen 9</span>
                        </div>
                    </div>
                    <div class="checkpoint-product-img">
                        <img src="../../images/products/iPad Gen 10 Wifi 64GB.jpg" alt="" style="padding: 5px;">
                        <div class="name-check">
                            <span>iPad Gen 10</span>
                        </div>
                    </div>
                    <div class="checkpoint-product-img">
                        <img src="../../images/products/ipad Air 6 M2 13 inch Wifi 128GB.jpg" alt="" style="padding: 5px;">
                        <div class="name-check">
                            <span>iPad Air</span>
                        </div>
                    </div>
                </div>
                `;
                customContent.appendChild(newContent);
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.love-product i').forEach(function (icon) {
            icon.addEventListener('click', function () {
                let idSP = this.dataset.idsp;
                let action = this.classList.contains('fa-heart-o') ? "add" : "remove";

                fetch('../../inc/handle_fav.php', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `idSP=${idSP}&action=${action}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data === "success") {
                        if (action === "add") {
                            this.classList.remove('fa-heart-o');
                            this.classList.add('fa-heart');
                            showSuccess(data.message || "Thêm vào yêu thích thành công!");
                        } else {
                            this.classList.remove('fa-heart');
                            this.classList.add('fa-heart-o');
                            showSuccess(data.message || "Đã xóa khỏi yêu thích!");
                        }
                    } else if (data === "not_logged_in") {
                        showError(data.message || "Vui lòng đăng nhập để sử dụng tính năng này!");
                    } else {
                        showError(data.message || "Lỗi khi xử lý yêu thích!");
                    }
                })
                .catch(error => console.error("Lỗi AJAX:", error));
            });
        });
    });

    function showError(message) {
        const errorDiv = document.getElementById("error-message");
        errorDiv.innerText = message;
        errorDiv.style.display = "block";

        setTimeout(() => {
            errorDiv.style.display = "none";
        }, 3000);
    }

    function showSuccess(message) {
        const successDiv = document.getElementById("success-message");
        successDiv.innerText = message;
        successDiv.style.display = "block";

        setTimeout(() => {
            successDiv.style.display = "none";
        }, 3000);
    }
</script>