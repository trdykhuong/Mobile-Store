<?php
require_once '../../Controller/cart/OderController.php';
require_once '../../Controller/cart/CartController.php';

$selected_products = $_SESSION['selected_products'] ?? [];
$cartItems = $_SESSION['cart'] ?? [];
$selected_cart = getSelectedProducts($selected_products, $cartItems);

?>

<title>Đơn hàng của bạn</title>
<link rel="stylesheet" href="../../css/cart/thanhtoan.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<script src="https://esgoo.net/scripts/jquery.js"></script>

<body>
    <header>
        <div class="header">
            <?php include('../trangchu/header.php') ?>
        </div>
    </header>

    <main class="main" id="main">
        <div class="container">
            <div class="checkout-detail">

                <!-- Thanh điều hướng -->
                <div class="nav-checkout-detail">
                    <div class="nav-name">
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

                <!-- Nội dung -->
                <div class="personal-infomation">
                    <div class="checkout-form">
                        <div class="title-info">
                            <a href="../../View/cart/cart.php">
                                <i class="fa-solid fa-arrow-left"></i>
                                <h4>Thông tin nhận hàng</h4>
                            </a>
                        </div>
                        <!-- Thông tin cá nhân đặt hàng -->
                        <div class="order-infomation">
                            <h4>Thông tin đơn hàng</h4>
                            <form action="check_out.php" method="post" onsubmit="return validateAddressSelection();">

                                <!-- Nội dung đầu -->
                                <div class="first-content">
                                    <div class="input-info">
                                        <input type="text" placeholder="Vui lòng nhập họ và tên (bắt buộc)" name="name" value="<?= htmlspecialchars($hoten) ?>" required>
                                    </div>
                                    <div class="input-info">
                                        <input type="tel" placeholder="Vui lòng nhập số điện thoại (bắt buộc)" name="phone" value="<?= htmlspecialchars($phone) ?>" required>
                                    </div>
                                    <div class="input-info">
                                        <input type="email" placeholder="Vui lòng nhập địa chỉ Email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                                    </div>
                                    <div class="input-info">
                                        <div class="css_select_div">
                                            <select class="css_select" id="tinh" name="tinh" title="Chọn Tỉnh Thành" required>
                                                <option value="0">Tỉnh Thành</option>
                                            </select>
                                            <select class="css_select" id="quan" name="quan" title="Chọn Quận Huyện" required>
                                                <option value="0">Quận Huyện</option>
                                            </select>
                                            <select class="css_select" id="phuong" name="phuong" title="Chọn Phường Xã" required>
                                                <option value="0">Phường Xã</option>
                                            </select>
                                        </div>
                                        <div class="input-info">
                                            <input type="text" id="specific-address" name="specific_address" placeholder="Nhập địa chỉ cụ thể (số nhà, đường...)" required>
                                        </div>
                                        <input type="hidden" id="full-address" class="checkout__input__add" name="address">
                                    </div>
                                    <div class="input-info">
                                        <input readonly type="text" name="discount_code" id="voucherValue" placeholder="Chọn mã giảm giá (nếu có)">
                                        <div class="btn-voucher">
                                            <p>Chọn Voucher</p>
                                            <i class="fa-solid fa-ticket"></i>
                                        </div>
                                    </div>

                                    <div id="modalOverlay" class="modal-overlay"></div>

                                    <div class="form-choice-voucher">
                                        <h2>Chọn Voucher</h2>
                                        <table>
                                            <tbody>
                                                <?php if ($vouchers->num_rows > 0): ?>
                                                    <?php while ($row = $vouchers->fetch_assoc()): ?>
                                                        <tr data-value="<?= $row['MAKHUYENMAI']; ?>">
                                                            <td>
                                                                <div class="voucher-content" data-id="<?= $row['MAKHUYENMAI']; ?>" data-value="<?= $row['CODE']; ?>" data-discount="<?= $row['GIATRI']; ?>">
                                                                    <p>🎟️ <?= $row['CODE']; ?></p>
                                                                    Giảm <?= number_format($row['GIATRI'], 0, ',', '.'); ?>đ
                                                                    <input type="radio" name="voucher_code" class="selectVoucher" value="<?php echo $row['MAKHUYENMAI'] ?>">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="2">Không có voucher nào khả dụng.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                        <div class="button-voucher">
                                            <button class="btn-return" type="button">Quay lại</button>
                                            <button class="btn-submit" type="button">Xác nhận</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nội dung thứ hai -->
                                <div class="second-content">
                                    <h4>Chọn dịch vụ vận chuyển</h4>
                                    <select name="shipping_method" id="shipping_method" required>
                                        <!-- <option value="">-- Chọn dịch vụ vận chuyển --</option> -->
                                        <?php while ($row = $shippingMethods->fetch_assoc()): ?>
                                            <option value="<?= $row['idVC'] ?>" data-price="<?= $row['GIAVANCHUYEN'] ?>">
                                                <?= $row['TENDVVC'] ?>: <?= number_format($row['GIAVANCHUYEN'], 0, ',', '.') ?> đ
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <!-- Nội dung thứ ba -->
                                <div class="third-content">
                                    <h4>Thông tin sản phẩm</h4>
                                    <ul class="selected-products-list">
                                        <?php
                                        $total = 0;
                                        foreach ($selected_cart as $idCTSP => $item):
                                            $soluong = $item['SOLUONG'] ?? 0;
                                            $gia = $item['GIA'] + $item['DIEUCHINHGIA'] - (($item['GIA'] + $item['DIEUCHINHGIA']) * ($item['DISCOUNT'] ?? 0) / 100);
                                            $discount = $item['DISCOUNT'] ?? 0;
                                            $tonkho = $item['TONKHO'] ?? 0;
                                            $mausac = $item['MAUSAC'] ?? 'NaN';
                                            $dungluong = $item['DUNGLUONG'] ?? 'NaN';
                                            $thanhtien = $soluong * $gia;
                                            $total += $thanhtien;

                                            // Kiểm tra còn hàng không
                                            $isOutOfStock = $tonkho < $soluong;
                                        ?>
                                            <li class="selected-product-item <?= $isOutOfStock ? 'out-of-stock' : '' ?>">
                                                <img src="../../images/products/<?= htmlspecialchars($item['IMG']) ?>" alt="<?= htmlspecialchars($item['TENSP']) ?>" class="product-thumb">
                                                <div class="order-product-detail">
                                                    <strong><?= htmlspecialchars($item['TENSP']) ?></strong>
                                                    <small>
                                                        <?= $item['MAUSAC'] ? "Màu: " . htmlspecialchars($item['MAUSAC']) : "" ?>
                                                        <?= $item['DUNGLUONG'] ? " | Dung lượng: " . htmlspecialchars($item['DUNGLUONG']) : "" ?>
                                                    </small>

                                                    <div class="order-product-detail-span">
                                                        <span>Số lượng: <?= $soluong ?></span>
                                                        <span>Đơn giá: <?= number_format($gia, 0, ',', '.') ?> đ</span>
                                                        <span class="total">Thành tiền: <?= number_format($thanhtien, 0, ',', '.') ?> đ</span>
                                                    </div>
                                                    <?php if ($isOutOfStock): ?>
                                                        <p class="warning">⚠️ Chỉ còn <?= $tonkho ?> sản phẩm trong kho!</p>
                                                    <?php endif; ?>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>

                                <!-- Nội dung thứ tư -->
                                <div class="fourth-content">
                                    <div class="price-info">
                                        <div class="price-info-content">
                                            <p>Tổng tiền sản phẩm:</p>
                                            <span>
                                                <?= number_format($total, 0, ',', '.') . " đ" ?>
                                            </span>
                                        </div>
                                        <div class="price-info-content">
                                            <p>Phí vận chuyển:</p>
                                            <span id="shipping_fee">0 đ</span>
                                        </div>
                                        <div class="price-info-content">
                                            <p>Giảm giá:</p>
                                            <span id="discount_amount">0 đ</span>
                                        </div>
                                        <div class="price-info-content">
                                            <strong>Tổng tiền thanh toán:</strong>
                                            <span id="final_total">
                                                <?= number_format($total, 0, ',', '.') . " đ" ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php foreach ($_SESSION['selected_products'] as $idCTSP): ?>
                                    <input type="hidden" name="selected_products[]" value="<?= htmlspecialchars($idCTSP) ?>">
                                <?php endforeach; ?>

                                <div class="end-content">
                                    <button type="submit" class="site-btn" name="btDathang">Đặt hàng</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <?php include('../trangchu/footer.php') ?>
    </footer>
</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let total = <?= json_encode($total ?? 0) ?>;

        let shippingSelect = document.getElementById("shipping_method");
        let shippingFeeElement = document.getElementById("shipping_fee");
        let discountElement = document.getElementById("discount_amount");
        let finalTotalElement = document.getElementById("final_total");

        if (!shippingSelect || !shippingFeeElement || !discountElement || !finalTotalElement) {
            console.error("Một hoặc nhiều phần tử không tồn tại!");
            return;
        }

        function updateTotal() {
            let selectedOption = shippingSelect.options[shippingSelect.selectedIndex];
            if (!selectedOption) return;

            let shippingFee = parseFloat(selectedOption.dataset.price || 0);
            let discount = parseFloat(discountElement.dataset.value || 0);
            let finalTotal = Math.max(0, total + shippingFee - discount);

            shippingFeeElement.innerText = shippingFee.toLocaleString() + " đ";
            finalTotalElement.innerText = finalTotal.toLocaleString() + " đ";
        }

        shippingSelect.addEventListener("change", updateTotal);

        // Cập nhật giảm giá khi chọn voucher
        const vouchers = document.querySelectorAll(".voucher-content");
        const voucherValue = document.getElementById("voucherValue");

        const btnSubmit = document.querySelector(".btn-submit");
        const btnOpenVoucher = document.querySelector(".btn-voucher");
        const btnCloseVoucher = document.querySelector(".btn-return");

        const formBackVoucher = document.querySelector(".modal-overlay");
        const formChoiceVoucher = document.querySelector(".form-choice-voucher");

        let selectedVoucher = null;
        let selectedDiscount = 0;

        vouchers.forEach(voucher => {
            voucher.addEventListener("click", function() {
                const radio = this.querySelector(".selectVoucher");
                if (radio) {
                    radio.checked = true;

                    // Lấy mã giảm giá
                    const code = this.getAttribute("data-value");
                    voucherValue.value = code;

                    // Lấy số tiền giảm giá từ data-discount
                    selectedDiscount = parseFloat(this.getAttribute("data-discount") || 0);

                    // Cập nhật discount_amount
                    discountElement.innerText = selectedDiscount.toLocaleString() + " đ";
                    discountElement.dataset.value = selectedDiscount;

                    selectedVoucher = code;
                    btnSubmit.disabled = false;

                    // Cập nhật tổng tiền ngay lập tức
                    updateTotal();
                }
            });
        });

        // Mở popup chọn voucher
        btnOpenVoucher?.addEventListener('click', function() {
            formBackVoucher?.classList.add('active');
            formChoiceVoucher?.classList.add('active');
        });

        // Đóng popup chọn voucher
        function closeModal() {
            formBackVoucher?.classList.remove('active');
            formChoiceVoucher?.classList.remove('active');
        }

        btnCloseVoucher?.addEventListener('click', closeModal);

        // Xác nhận voucher và lưu vào session
        btnSubmit?.addEventListener('click', function() {
            if (selectedVoucher) {
                fetch('cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'selectedVoucher=' + encodeURIComponent(selectedVoucher)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Lỗi khi gửi voucher!');
                        }
                        return response.text();
                    })
                    .then(() => {
                        closeModal(); // Đóng modal khi đã chọn voucher
                    })
                    .catch(error => {
                        console.error("Lỗi:", error);
                    });
            }
        });

        // Mặc định vô hiệu hóa nút xác nhận khi mở form
        if (btnSubmit) {
            btnSubmit.disabled = true;
        }
    });

    // JS chọn tỉnh thành, gọi API lấy quận huyện, phường xã
    $(document).ready(function() {
        // Lấy tỉnh thành
        $.getJSON('https://esgoo.net/api-tinhthanh/1/0.htm', function(data_tinh) {
            if (data_tinh.error == 0) {
                $.each(data_tinh.data, function(key_tinh, val_tinh) {
                    $("#tinh").append('<option value="' + val_tinh.id + '">' + val_tinh.full_name + '</option>');
                });
                $("#tinh").change(function(e) {
                    var idtinh = $(this).val();
                    // Lấy quận huyện
                    $.getJSON('https://esgoo.net/api-tinhthanh/2/' + idtinh + '.htm', function(data_quan) {
                        if (data_quan.error == 0) {
                            $("#quan").html('<option value="0">Quận Huyện</option>');
                            $("#phuong").html('<option value="0">Phường Xã</option>');
                            $.each(data_quan.data, function(key_quan, val_quan) {
                                $("#quan").append('<option value="' + val_quan.id + '">' + val_quan.full_name + '</option>');
                            });
                            // Lấy phường xã
                            $("#quan").change(function(e) {
                                var idquan = $(this).val();
                                $.getJSON('https://esgoo.net/api-tinhthanh/3/' + idquan + '.htm', function(data_phuong) {
                                    if (data_phuong.error == 0) {
                                        $("#phuong").html('<option value="0">Phường Xã</option>');
                                        $.each(data_phuong.data, function(key_phuong, val_phuong) {
                                            $("#phuong").append('<option value="' + val_phuong.id + '">' + val_phuong.full_name + '</option>');
                                        });
                                    }
                                });
                            });
                        }
                    });
                });
            }
        });

        // Cập nhật địa chỉ đầy đủ
        function updateFullAddress() {
            const province = $("#tinh option:selected").text() || "";
            const district = $("#quan option:selected").text() || "";
            const ward = $("#phuong option:selected").text() || "";
            const specificAddress = $("#specific-address").val() || "";
            const fullAddress = `${specificAddress}, ${ward}, ${district}, ${province}`;
            $("#full-address").val(fullAddress);
        }

        // Lắng nghe sự kiện thay đổi
        $("#tinh, #quan, #phuong, #specific-address").on("change input", updateFullAddress);
    });

    // Kiểm tra địa chỉ đã được chọn đầy đủ chưa
    function validateAddressSelection() {
        const province = $("#tinh").val();
        const district = $("#quan").val();
        const ward = $("#phuong").val();

        if (province === "0") {
            alert("Tỉnh/Thành phố nào? Sao không chọn?");
            return false;
        }

        if (district === "0") {
            alert("Chọn Quận/Huyện điiii.");
            return false;
        }

        if (ward === "0") {
            alert("Không có Phường/Xã àaa.");
            return false;
        }

        return true;
    }
</script>