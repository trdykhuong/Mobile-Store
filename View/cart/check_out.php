<?php
require_once '../../Controller/cart/CheckOutController.php';
?>



<title>Xác nhận đơn hàng</title>
<link rel="stylesheet" href="../../css/cart/checkout.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<body>
    <header>
        <div class="header">
            <?php include('../trangchu/header.php'); ?>
        </div>
    </header>

    <main class="main" id="main">
        <div class="container">
            <div class="checkout-detail">
                <!-- Thanh điều hướng -->
                <div class="nav-checkout-detail">
                    <div class="checkout-name">
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

                <!-- Nội dung thanh toán -->
                <div class="checkout-content">
                    <div class="checkout-info">
                        <div class="title-info">
                            <h2>Xác nhận đơn hàng</h2>
                        </div>

                        <div class="checkout-detail-info">
                            <div class="order-info">
                                <p><strong>Họ tên:</strong> <?= htmlspecialchars($name) ?></p>
                                <p><strong>Điện thoại:</strong> <?= htmlspecialchars($phone) ?></p>
                                <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
                                <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($address) ?></p>
                                <p><strong>Dịch vụ vận chuyển:</strong> <?= htmlspecialchars($tenDVVC) ?> - <?= number_format($phivc, 0, ',', '.') ?> đ</p>
                                <p><strong>Khuyến mãi:</strong> <?= htmlspecialchars($code) ?> - giảm <?= number_format($giamgia, 0, ',', '.') ?> đ</p>
                            </div>

                            <h3>Chi tiết đơn hàng</h3>
                            <table class="order-details">
                                <thead>
                                    <tr>
                                        <th>Hình ảnh</th>
                                        <th>Sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Đơn giá</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($selected_cart)): ?>
                                        <tr>
                                            <td colspan="5">Không có sản phẩm nào trong giỏ hàng</td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php 
                                    $thanhtien = 0;
                                    foreach ($selected_cart as $idSP => $item):
                                        $soluong = (int)$item['SOLUONG'];
                                        $gia = (float)$item['GIA'] + $item['DIEUCHINHGIA'] - (($item['GIA'] + $item['DIEUCHINHGIA']) * ($item['DISCOUNT'] ?? 0) / 100);
                                        $discount = (float)($item['DISCOUNT'] ?? 0);
                                        $tien = $soluong * $gia;
                                        $thanhtien += $soluong * $gia;
                                        $mau = htmlspecialchars($item['MAUSAC'] ?? 'Không rõ');
                                        $dungluong = htmlspecialchars($item['DUNGLUONG'] ?? 'Không rõ');
                                    ?>
                                        <tr>
                                            <td>
                                                <img src="../../images/products/<?= htmlspecialchars($item['IMG']) ?>" alt="<?= htmlspecialchars($item['TENSP']) ?>" width="80">
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($item['TENSP']) ?></strong><br>
                                                <small>Màu sắc: <?= $mau ?> | Dung lượng: <?= $dungluong ?></small>
                                            </td>
                                            <td><?= $soluong ?></td>
                                            <td><?= number_format($gia, 0, ',', '.') ?>đ</td>
                                            <td><?= number_format($tien, 0, ',', '.') ?>đ</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4"><strong>Tổng tiền sản phẩm:</strong></td>
                                        <td class="price-tfoot"><?= number_format($thanhtien, 0, ',', '.') ?>đ</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><strong>Phí vận chuyển:</strong></td>
                                        <td class="price-tfoot"><?= number_format($phivc, 0, ',', '.') ?>đ</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><strong>Giảm giá:</strong></td>
                                        <td class="price-tfoot"><?= number_format($giamgia, 0, ',', '.') ?>đ</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><strong>Tổng tiền thanh toán:</strong></td>
                                        <td class="price-tfoot"><?= number_format($thanhtien + $phivc - $giamgia, 0, ',', '.') ?>đ</td>
                                    </tr>
                                </tfoot>
                            </table>


                            <form action="" method="post">
                                <div class="checkout-input">
                                    <p>Chọn phương thức thanh toán: <span>*</span></p>
                                    <?php
                                    $selected_payment = $_SESSION['payment_method'] ?? null;
                                    while ($row = $paymentMethods->fetch_assoc()):
                                    ?>
                                        <div class="choice-input">
                                            <input type="radio" name="payment_method" value="<?= $row['idTHANHTOAN'] ?>"
                                                <?= ($selected_payment == $row['idTHANHTOAN']) ? 'checked' : '' ?> required>
                                            <?php echo htmlspecialchars($row['TENPHUONGTHUC']); ?>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                                <div class="checkout-btn">
                                    <button type="button" onclick="window.location.href='order_infor.php'" class="btn-return-page">Quay lại</button>
                                    <button type="submit" name="confirmOrder" class="btn-pay">Đặt đơn</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <?php include('../trangchu/footer.php'); ?>
    </footer>
</body>