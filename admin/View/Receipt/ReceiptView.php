<!-- CSS chung -->
<link rel="stylesheet" href="../../css/admin/OneForAll.css">
<link rel="stylesheet" href="../../css/admin/Receipt.css">

<body onload="OnloadData()">
    <div class="header">    
        <div class="first-header">
            <p>Quản lý phiếu nhập</p>
        </div>
        <div class="second-header">
            <div class="second-header-main">
                <button class="home">
                    <a href="?page=employeeinfo"> 
                        <i class="fa-solid fa-house home-outline"></i>
                    </a>
                </button>
                <div class="line"></div>
                <button onclick="OpenAddReceiptPop()" class='btn-add'>Thêm phiếu nhập</button>
            </div>
        </div>
    </div>

    <!-- Nội dung chính -->
    <main class="main">
        <div class="container">
            <div class="filter">
                <div class="filter-search">
                    <input id='keyword' name="search-filter" placeholder="Nhập từ khóa" oninput="LoadReceipt()"></input>
                </div>
                <div class="filter-date">
                    <span>Ngày nhập:</span>
                    <input type="date" name="date-search" id="date-serch" onchange="LoadReceipt()">
                </div>
                <div class="filter-merge">
                    <span>Sắp xếp: </span>
                    <select id="order-price" onchange="LoadReceipt()">
                        <option value="0" selected>--Sắp xếp theo--</option>
                        <option value="1">Giá tăng dần</option>
                        <option value="2">Giá giảm dần</option>
                    </select>
                </div>
            </div>

            <div class="receipt-list">
                <table>
                    <thead>
                        <tr>
                            <th>Mã phiếu nhập</th>
                            <th>Nhà cung cấp</th>
                            <th>Địa chỉ</th>
                            <th>Ngày nhập</th>
                            <th>Thành tiền</th>
                            <th>Lợi nhuận</th>
                        </tr>
                    </thead>
                    <tbody id='hang'></tbody>
                </table>
                <div id='result'></div>
            </div>

            <div class='modal' id='modal'>
                <div class="receiptDetail-form" id="receiptDetail-form">
                    <h1>Chi tiết phiếu nhập</h1>
                    <div class="receiptDetail-form-main">
                        <div class="receiptDetail-form-info">
                            <p>
                                <strong>Mã phiếu nhập:</strong>
                                <span id="maPN"></span>
                            </p>
                            <p>
                                <strong>Nhà cung cấp:</strong> 
                                <span id="ncc-name"></span>
                            </p>
                            <p>
                                <strong>Ngày nhập:</strong> 
                                <span id="ngay-nhap"></span>
                            </p>
                        </div>
                      
                        <div class='receiptdetail-container'>
                            <table>
                                <thead>
                                    <tr>
                                    <th style="width: 25%;">Tên sản phẩm</th>
                                    <th style="width: 15%;">Màu sắc</th>
                                    <th style="width: 15%;">Dung lượng</th>
                                    <th style="width: 15%;">Giá nhập</th>
                                    <th style="width: 15%;">Giá bán</th>
                                    <th style="width: 10%;">Số lượng</th>
                                    </tr>
                                </thead>
                                <tbody id='hang-sp'></tbody>
                            </table>
                        </div>

                        <div class="receiptDetail-form-btn">
                            <button id='close-receiptdetail' type="button" onclick="closeReceiptDetailPop()">Trở lại</button>
                        </div>
                    </div>
                </div>

                <div id='addReceipt-popup' class="addReceipt-popup">
                    <?php include('InsertReceiptView.php');?>
                </div>
            </div>
        </div>
    </main>
</body>
<script src="../../js/admin/Receipt.js"></script>
