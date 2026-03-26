<!-- CSS chung -->
<link rel="stylesheet" href="../../css/admin/OneForAll.css">
<link rel="stylesheet" href="../../css/admin/Products.css">
<link rel="stylesheet" href="../../css/admin/ProductFilter.css">

<body onload="OnloadData()">
    <div class="header">    
        <div class="first-header">
            <p>Quản lý sản phẩm</p>
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
        <div class="container">
            <div class="filter" id="filter">
                <div class="first-filter">
                    <div class="first-filter-left">
                        <input name="search-filter" placeholder="Nhập từ khóa tìm kiếm"/>
                        <button type="button" class="btn-search"><i class="fa-solid fa-search"></i></button>
                    </div>
                    <div class="first-filter-right">
                        <button type="button" class="btn-open-filter" onclick="openFilterForm()"><i class="fa-solid fa-filter"></i></button>
                    </div>
                </div>

                <div class="second-filter" id="second-filter">
                    <div class="second-filter-content">
                        <div class="first-content">
                            <div class="content-brand">
                                <span>Hãng</span>
                                <select name="filter-Hang" class="filter-form">
                                    <option value="0" selected>-- Hãng --</option>
                                </select>
                            </div>

                            <div class="content-brand">
                                <span>Danh mục</span>
                                <select name="filter-Danhmuc" class="filter-form">
                                    <option value="0" selected>-- Danh mục --</option>
                                </select>
                            </div>
                        </div>

                        <div class="second-content">
                            <span>Giá</span>
                            <div class="content-brand-second">
                                <input type="number" name="gianhap-from" placeholder="Nhập thấp nhất"/>
                                --
                                <input type="number" name="gianhap-to" placeholder="Nhập cao nhất" />
                            </div>
                        </div>

                        <div class="third-content">
                            <label for="orderby">Trạng thái:</label>
                            <div class="content-brand-third">
                                <select name="orderby" class="filter-form">
                                    <option value="1">Mở Khóa</option>
                                    <option value="0">Khóa</option>
                                </select>
                            </div>
                        </div>

                        <div class="fourth-content">
                            <button type="button" class="close-filter-form" onclick="closeFilterForm()">Trở lại</button>
                            <button type="button" class="filter-form" name="filter">Xác nhận</button>
                        </div>
                    </div>
                </div>    
            </div>

            <div class="product-content">
                <div class="product-content-main">
                    <table>
                        <thead>
                            <tr>
                                <th width="34%">Sản phẩm</th>
                                <th width="10%">Hãng</th>
                                <th width="12%">Danh mục</th>
                                <th>Giá nhập (VND)</th>
                                <th>Giá bán (VND)</th>
                                <th>Giảm giá</th>
                            </tr>
                        </thead>
                        <tbody id='productRows'></tbody>
                    </table>
                    <div id='result'></div>
                </div>

                <div class='modal' id='modal'>
                    <div class="product-detail" id="product-detail-form" >
                        <h1>Chi tiết sản phẩm</h1>
                        <div class="content-detail">
                            <input type="hidden" value='' name='trangthai'>
                            <div class="left-content">
                                <img id="img-updt" src="" alt="">  
                                <button type="button" onclick="document.getElementById('file-upload').click()">Chọn ảnh</button>
                                <input id="file-upload" type="file" name="Img" accept="image/png, image/gif, image/jpeg" onchange="hienThiAnh(event, 'updt')">  
                            </div>

                            <div class="center-content">
                                <span>
                                    <strong>Mã sản phẩm:</strong>
                                    <p name='maSP'></p>
                                </span>

                                <span>
                                    <strong>Tên sản phẩm:</strong>
                                    <p name='Tensp-updt' value=""></p>
                                </span>

                                <span>
                                    <strong>Giá nhập:</strong>
                                    <p name="gianhap"></p>
                                </span>

                                <span>
                                    <strong>Giá bán:</strong>
                                    <p name="giaban"></p>
                                </span>

                                <span>
                                    <strong>Giá thêm:</strong>
                                    <p id="giathem_updt" name="giathem"></p>
                                </span>

                                <span>
                                    <strong>Lợi nhuận:</strong>
                                    <p name="loinhuan"></p>
                                </span>
                            </div>

                            <div class="content-line"></div>

                            <div class="right-content">
                                <span>
                                    <strong>                                    
                                        <label for="GiamGia-updt">Mức giảm:</label>
                                    </strong>
                                    <div class="discount">
                                        <input type="number" name="GiamGia-updt" value="" min='0' max='99'> %
                                    </div>
                                </span>

                                <span>
                                    <strong>
                                        <label for="Hang">Hãng:</label>
                                    </strong>
                                    <select id='Hang-updt' name="Hang"></select>
                                </span>

                                <span>   
                                    <strong>
                                        <label for="Danhmuc">Danh mục:</label>
                                    </strong>
                                    <select id='Danhmuc-updt' name="Danhmuc"></select> 
                                </span>

                                <span>   
                                    <strong>
                                        <label for="Chatlieu"> Chất liệu:</label>
                                    </strong>
                                    <select id="variant-title" name="variant-title" readonly></select> 
                                </span>

                                <span>
                                    <strong>
                                        <label for="Mota-updt">Mô tả:</label>
                                    </strong>
                                    <textarea type="text" name="Mota-updt"></textarea>
                                </span>
                            </div>
                        </div>

                        <div class="end-content-detail-product">
                            <button id='close-detail' class="close" type="button" onclick="CloseDetail()">Trở lại</button>
                            <button id="re-btn" class="block" onclick="Handle()"></button>
                            <button id="update" class="update">Cập nhật</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

<script src="../../js/admin/Product.js"></script>