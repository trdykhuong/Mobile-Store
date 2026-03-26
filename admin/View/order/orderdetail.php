<?php include("../Controller/order/orderdetail.php"); ?>

<!-- CSS chung -->
<link rel="stylesheet" href="../../css/admin/OneForAll.css">
<link rel="stylesheet" href="../../css/admin/orderdetail.css">

<body>
    <div class="header">    
        <div class="first-header">
            <p>Chi tiết đơn hàng số <?php echo htmlspecialchars($order["idHD"]); ?></p>
        </div>
        <div class="second-header">
            <div class="second-header-main">
                <button class="home">
                    <a href="?page=employeeinfo"> 
                        <i class="fa-solid fa-house home-outline"></i>
                    </a>
                </button>
                <div class="line"></div>
            </div>
        </div>
    </div>

    <!-- Nội dung chính -->
    <main class="main">
        <div class='container'>
            <div class="first-order-detail">
                <a href="?page=order" class="btn-back">
                    <i class="fa fa-arrow-left"></i>
                    <h2>Quay lại đơn hàng</h2>
                </a>
            </div>

            <div class="second-order-detail">
                <div class="order-info">
                    <h3>Thông tin khách hàng</h3>
                    <p>
                        <strong>Mã đơn hàng:</strong> 
                        <span><?php echo htmlspecialchars($order["idHD"]); ?></span>
                    </p>
                    <p>
                        <strong>Khách hàng:</strong> 
                        <span><?php echo htmlspecialchars($order["khachhang"]); ?></span>
                    </p>
                    <p>
                        <strong>Ngày đặt:</strong> 
                        <span><?php echo htmlspecialchars($order["NGAYMUA"]); ?></span>
                    </p>
                    <p>
                        <strong>Địa chỉ giao hàng:</strong> 
                        <span><?php echo htmlspecialchars($order["DIACHI"]); ?></span>
                    </p>
                    <p>
                        <strong>Tổng tiền:</strong> 
                        <span><?php echo number_format($order["THANHTIEN"], 0, ',', '.'); ?> VND</span>
                    </p>
                </div>

                <div class="line-order-detail"></div>

                <div class="status-update">
                    <h3>Cập nhật trạng thái đơn hàng</h3>
                    <form method="POST">
                        <input type="hidden" name="idHD" value="<?php echo $idHD; ?>">

                        <!-- Chờ xác nhận -->
                        <label style="display: <?= ($order['idSTATUS'] > 1) ? 'none' : 'block' ?>">
                            <input type="radio" name="status" value="1" <?php echo ($order["idSTATUS"] == 1) ? "checked" : ""; ?>> Chờ xác nhận
                        </label>

                        <!-- Đang chuẩn bị giao hàng (Chỉ hiển thị nếu đơn chưa đến bước "Đang giao hàng" hoặc cao hơn) -->
                        <label style="display: <?= ($order['idSTATUS'] >= 3) ? 'none' : 'block' ?>">
                            <input type="radio" name="status" value="2" <?php echo ($order["idSTATUS"] == 2) ? "checked" : ""; ?>> Đang chuẩn bị giao hàng
                        </label>

                        <!-- Đang giao hàng (Chỉ hiển thị nếu đơn chưa hoàn tất hoặc hủy) -->
                        <label style="display: <?= ($order['idSTATUS'] >= 4) ? 'none' : 'block' ?>">
                            <input type="radio" name="status" value="3" <?php echo ($order["idSTATUS"] == 3) ? "checked" : ""; ?>> Đang giao hàng
                        </label>

                        <!-- Giao hàng thành công (Chỉ có thể chọn nếu đơn đang giao) -->
                        <label style="display: <?= ($order['idSTATUS'] != 3) ? 'none' : 'block' ?>">
                            <input type="radio" name="status" value="4" <?php echo ($order["idSTATUS"] == 4) ? "checked" : ""; ?>> Giao hàng thành công
                        </label>

                        <label style="display: <?= ($order['idSTATUS'] != 4) ? 'none' : 'block' ?>">
                            <input type="radio" name="status" value="4" <?php echo ($order["idSTATUS"] == 4) ? "checked" : ""; ?>> Giao hàng thành công
                        </label>

                        <!-- Hủy đơn hàng (Chỉ cho phép hủy nếu đơn chưa giao thành công) -->
                        <label style="display: <?= ($order['idSTATUS'] >= 4) ? 'none' : 'block' ?>">
                            <input type="radio" name="status" value="5" <?php echo ($order["idSTATUS"] == 5) ? "checked" : ""; ?>> Hủy đơn hàng
                        </label>

                        <button type="submit" class="update-btn">Cập nhật trạng thái</button>
                    </form>
                </div>
            </div>

            <div class="third-order-content">
                <h3>Danh sách sản phẩm</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $products->fetch_assoc()) { ?>
                            <tr>
                                <td>
                                    <img src="../../images/products/<?php echo htmlspecialchars($row["IMG"]); ?>" alt="" width="100">
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($row["TENSP"]); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($row["SOLUONG"]); ?>
                                </td>
                                <td>
                                    <?php echo number_format($row["GIA"], 0, ',', '.'); ?> VND
                                </td>
                                <td>
                                    <?php echo number_format($order["THANHTIEN"], 0, ',', '.'); ?> VND
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
<script src="../../js/admin/orderdetail.js"></script>