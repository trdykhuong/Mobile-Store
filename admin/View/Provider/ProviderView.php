<!-- CSS chung -->
<link rel="stylesheet" href="../../css/admin/OneForAll.css">
<link rel="stylesheet" href="../../css/admin/Provider.css">

<body onload="OnloadData()">
    <div class="header">    
        <div class="first-header">
            <p>Quản lý nhà cung cấp</p>
        </div>
        <div class="second-header">
            <div class="second-header-main">
                <button class="home">
                    <a href="?page=employeeinfo"> 
                        <i class="fa-solid fa-house home-outline"></i>
                    </a>
                </button>
                <div class="line"></div>
                <button id="add-ncc" class="add-ncc">Thêm nhà cung cấp</button>
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
                        <button><i class="fa-solid fa-search"></i></button>
                    </div>

                    <div class="first-filter-right">
                        <label for="orderby">Trạng thái:</label>
                        <div class="content-brand-third">
                            <select name="order" class="filter-form">
                                <option value="1" selected>Mở Khóa</option>
                                <option value="0">Khóa</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="provider-list">
                <table>
                    <thead>
                        <tr>
                            <th>Mã NCC</th>
                            <th>Tên Nhà cung cấp</th>
                            <th>Số điện thoại</th>  
                            <th>Địa chỉ</th>
                        </tr>
                    </thead>
                    <tbody id='providerRows'></tbody>
                </table>
                <div id='result'></div>
            </div>

            <div class='modal' id='modal'>
                <div class="NCCDetail-form" id="NCCDetail-form" >
                    <h1>Chi tiết nhà cung cấp</h1>
                    <div class="NCCDetail-form-content">
                        <input type="hidden" value='' name='trangthai-updt'>
                        <div class="NCCDetail-form-content-main">
                            <span>
                                <strong>Mã nhà cung cấp:</strong>
                                <p name='maNCC'></p>
                            </span>
                            <span>
                                <strong>Tên nhà cung cấp:</strong>
                                <input type='text' id='name-updt' name="name-updt"></input>
                            </span>
                            <span>
                                <strong>Số điện thoại:</strong>
                                <input type='text' id='sdt-updt' name="sdt-updt"></input>
                            </span>
                            <span>
                                <strong>Địa chỉ:</strong>
                                <input type='text' id='diachi-updt' name="diachi-updt"></input>
                            </span>
                        </div>
                        <div class="NCCDetail-form-content-footer">
                            <button id='close-detail' class="close-detail" type="button" onclick="CloseDetail()">Trở lại</button>
                            <button id="re-btn" class="block" onclick="Handle()"></button>
                            <button id="update" class="update">Cập nhật</button>
                        </div>
                    </div>
                </div>

                <div class="NCCAdd-form" id="NCCAdd-form">
                    <h1>Thêm nhà cung cấp</h1>
                    <div class="NCCAdd-form-content">
                        <div class="NCCAdd-form-content-main">
                            <span>
                                <strong>Tên nhà cung cấp:</strong>
                                <input type='text' id='name-add' name="name-add"></input>
                            </span>
                            <span>
                                <strong>Số điện thoại:</strong>
                                <input type='text' id='sdt-add' name="sdt-add" max='10'></input>
                            </span>
                            <span>
                                <strong>Địa chỉ:</strong>
                                <input type='text' id='diachi-add' name="diachi-add"></input>
                            </span>
                        </div>
                        <div class="NCCAdd-form-content-footer">
                            <button id='close-add' class="close-add" type="button">Trở lại</button>
                            <button id="add" class="add">Thêm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
<script src="../../js/admin/Provider.js"></script>