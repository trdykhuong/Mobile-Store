<?php
require_once '../../Controller/cart/CartController.php';
?>

<title>Giỏ hàng của bạn</title>
<link rel="stylesheet" href="../../css/cart/cart.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<body>
    <header>
        <div class="header">
            <?php include('../trangchu/header.php'); ?>
        </div>
    </header>

    <main class="main" id="main">
        <div class="container">
            <div class="cart-detail">
                <!-- Thanh điều hướng -->
                <div class="nav-cart-detail">
                    <div class="cart-name">
                        <p>
                            <a href="../trangchu/trangchu.php"><span>Trang chủ</span></a> / Đơn hàng của bạn
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

                <!-- Nội dung giỏ hàng -->
                <div class="cart-content">
                    <h2>Giỏ hàng của bạn</h2>
                    <?php if (isset($cartItems) && count($cartItems) > 0) : ?>
                        <!-- Nội dung danh sách sản phẩm -->
                        <form id="cart-form" method="POST" action="order_infor.php">
                            <div class="cart-content-left">
                                <table>
                                    <thead>
                                        <tr>
                                            <td colspan="5">
                                                <div class="head-cart-content-left">
                                                    <div class="checkbox-cart-content-left">
                                                        <input type="checkbox" id="select-all-cart">
                                                        <span>Chọn tất cả</span>
                                                    </div>
                                                    <a href="cart.php?clear=true" class="clear-all"><i class="fa-solid fa-trash-can"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $tongtien = 0;
                                        foreach ($cartItems as $item):
                                            $idCTSP = $item['idCTSP']; // <-- Thêm dòng này để dễ hiểu
                                            $soluong = $item['SOLUONG'] ?? 0;
                                            $stock = $item['TONKHO'] ?? 0;
                                            $mausac = $item['MAUSAC'] ?? '';
                                            $dungluong = $item['DUNGLUONG'] ?? '';
                                            $gia = $item['GIA'] + $item['DIEUCHINHGIA'] - (($item['GIA'] + $item['DIEUCHINHGIA']) * ($item['DISCOUNT'] ?? 0) / 100);
                                            $thanhtien = $soluong * $gia;
                                            $tongtien += $thanhtien;
                                            $isOutOfStock = $stock <= 0;
                                        ?>
                                            <tr data-id="<?= $idCTSP ?>" class="<?= $isOutOfStock ? 'out-of-stock' : '' ?>">
                                                <td>
                                                    <div class="cart-product">
                                                        <div class="first-cart-product">
                                                            <div class="cart-product-img">
                                                                <input type="checkbox" class="cart-product-select" name="selected_products[]" value="<?= $idCTSP ?>" <?= $isOutOfStock ? 'disabled' : '' ?>>
                                                                <img src="../../images/products/<?= $item['IMG'] ?>" class="cart-img">
                                                            </div>
                                                            <div class="cart-product-info">
                                                                <h4><?= $item['TENSP'] ?></h4>
                                                                <p>Màu sắc: <?= $mausac ?></p>
                                                                <p>Dung lượng: <?= $dungluong ?></p>
                                                                <span class="price" data-gia="<?= $gia ?>">
                                                                    <span class="selling-price">
                                                                        <?= number_format($gia, 0, ',', '.') ?>đ
                                                                    </span>
                                                                    <span class="basis-price">
                                                                        <?= number_format($item['GIA'], 0, ',', '.') ?>đ
                                                                    </span>
                                                                </span>
                                                                <?php if ($isOutOfStock): ?>
                                                                    <span class="out-of-stock-label">Hết hàng</span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="second-cart-product">
                                                            <div class="cart-product-quantity">
                                                                <input type="hidden" class="idSP" value="<?= $item['idSP'] ?>">
                                                                <input type="hidden" class="idCTSP" value="<?= $item['idCTSP'] ?>">
                                                                <button type="button" class="btn-minus" <?= $isOutOfStock ? 'disabled' : '' ?>>-</button>
                                                                <input type="text" class="soluong" name="soluong[<?= $item['idCTSP'] ?>]" value="<?= $soluong ?>" readonly>
                                                                <button type="button" class="btn-plus" <?= $isOutOfStock ? 'disabled' : '' ?>>+</button>
                                                            </div>
                                                            <div class="thanhtien">
                                                                <?= number_format($thanhtien, 0, ',', '.') ?>đ
                                                            </div>
                                                            <a href="cart.php?remove=<?= $idCTSP ?>"><i class="fa-solid fa-trash-can"></i></a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>

                                </table>
                            </div>

                            <!-- Nội dung thanh toán -->
                            <div class="cart-content-right">
                                <div class="total-amount">
                                    <div class="total-amount-price">
                                        <h3>Tổng thanh toán ( <span id="selectedCount">0</span> sản phẩm )</h3>
                                        <span id="tongtien"><?= number_format($tongtien, 0, ',', '.') ?>đ</span>
                                    </div>
                                    <div class="btn-amount">
                                        <button type="submit" id="buy-now-btn" disabled>Mua Hàng</button>
                                        <p id="no-product-selected" style="color: red; display: none; text-align: center;">Không có sản phẩm nào được chọn</p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    <?php else: ?>
                        <p>Giỏ hàng của bạn đang trống.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include('../trangchu/footer.php'); ?>
</body>

<script src="../../js/cart/cart.js"></script>