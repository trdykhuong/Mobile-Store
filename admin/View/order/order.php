<?php include_once(__DIR__ . "/../../../Model/db_connect.php"); ?>

<!-- CSS chung -->
<link rel="stylesheet" href="../../css/admin/OneForAll.css">
<link rel="stylesheet" href="../../css/admin/order.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<body>
    <div class="header">    
        <div class="first-header">
            <p>Quản lý đơn hàng</p>
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
            <!-- Bộ lọc trạng thái -->  
            <div class="first-order-container">
                <label for="filterStatus">Trạng thái:</label>
                <select id="filterStatus" class="filter-Status">
                    <option value="all">Tất cả</option>
                    <?php
                        $db = new Database();
                        $conn = $db->connect();

                        $sql_status = "SELECT * FROM trangthaidonhang";
                        $result_status = $connect->query($sql_status);
                        while ($row_status = $result_status->fetch_assoc()) {
                            echo "<option value='{$row_status['idSTATUS']}'>{$row_status['STATUS']}</option>";
                        }
                        $connect->close();
                    ?>
                </select>
            </div>

            <!-- Khu vực hiển thị đơn hàng -->
            <div id="orderList" class="order-list"></div>

        </div>
    </main>
</body>

<script src="../../js/admin/order.js"></script>