<link rel="stylesheet" href="../../css/admin/insertReceiptView.css">

<div class="addreceipt-form" id="addreceipt-form">
    <h1>Thêm phiếu nhập</h1>
    <div id='input-receipt' class="input-receipt">
        <h3>Nhập thông tin phiếu</h3>
        <div class="input-receipt-content">
            <div id='input-receiptinfo' class="input-receiptinfo">
                <div class="item-input">
                    <Label for='input-ncc'>Nhà cung cấp:</Label>
                    <select name="input-ncc" id="input-ncc">
                    </select>
                </div>
                <div class="item-input">
                    <label for="input-loinhuan">Lợi nhuận: </label>
                    <input type="number" min='1' value='1' max='99' name='input-loinhuan' id='input-loinhuan' onchange="OnchangeLoiNhuan()"> (%)
                </div>
            </div>

            <div id='input-receiptdetail' class="input-receiptdetail">
                <div class="item-detail">
                    <Label for='input-listSP'>Chọn sản phẩm:</Label>
                    <select name="input-listSP" id="input-listSP" onchange="Display()">
                    </select>
                </div>

                <div class="item-detail">
                    <div id="co-bl" class="co-bl" style="display: none;">
                        <Label for='input-listC'>Màu sắc:</Label>
                        <select name="input-listC" id="input-listC">
                        </select>
                    </div>

                    <div id="dl-bl" class="dl-bl" style="display: none;">
                        <Label for='input-listR'>Dung lượng:</Label>
                        <select name="input-listR" id="input-listR">
                        </select>
                    </div>
                    
                    <div id="priceAdd" class="add-price" style="display: none;">
                        <label for="">Giá thêm: </label>
                        <input type="number" id='input-congthem' min='0' value="0">
                    </div>
                </div>

                <div class="item-detail">
                    <div class="item-detail-1">
                        <label for="">Giá nhập: </label>
                        <input type="text" id='input-gianhap'>
                    </div>

                    <div class="item-detail-1">
                        <label for="">Số lượng:</label>
                        <input type="number" id='input-soluong' min='1' value='1'>
                    </div>                 
                </div>
                
                <div class="item-receipt-btn">
                    <button id='btn-addSP' onclick="AddProduct()">Xác nhận</button>
                    <button id="addProduct" onclick="OpenAddProductPop()">Sản phẩm mới</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-receipt">
        <h3>Danh sách sản phẩm</h3>
        <div id="scrollView-SP" class="scrollView-SP"></div>
    </div>
    
    <div class="thanhtien">
        <p>Thành tiền:</p>
        <p id="thanhtien-sp">0</p>
    </div>

    <div class="footer-btn">
        <button id='close-receipt' type="button" onclick="CloseAddReceiptPop()">Trở lại</button>
        <button id='submit-addreceipt' class="submit-addreceipt" type="button" onclick="InsertReceipt()">Thêm</button>
    </div>
</div>

<div class="addproduct-container" id='addproduct-container'>
    <h1>Thêm sản phẩm</h1>
    <div class="addproduct-form">
        <div class="addproduct-form-img">
            <img id="img" src="" alt="">
            <input type="file" name="Img" id="change-img" accept="image/png, image/gif, image/jpeg" onchange="hienThiAnh(event)">
            <button type="button" onclick="document.getElementById('change-img').click()">Chọn ảnh</button>
        </div>
        <div class="addproduct-form-input">
            <div class="item-addproduct">
                <label for="Tensp">Tên sản phẩm: </label>
                <input type="text" name="Tensp" placeholder="Nhập tên sản phẩm.">
            </div>

            <div class="item-addproduct">
                <label for="hang">Hãng: </label>
                <select name="hang"></select>
            </div>

            <div class="item-addproduct">
                <label for="danhmuc">Danh mục</label>
                <select id="danhmuc" name="danhmuc"></select>
            </div>

            <div id="color_displ" class="item-addproduct" style="display: none;">
                <label for="Mausac">Màu sắc: </label>
                <input type="text" name="mausac" placeholder="Nhập màu mới.">
            </div>

            <div id="dl_displ" class="item-addproduct" style="display: none;">
                <label for="Dungluong">Dung lượng: </label>
                <select name="dl">
                    <option value="KHÔNG CÓ">Vui lòng chọn dung lượng</option>
                    <option value="128GB">128GB</option>
                    <option value="256GB">256GB</option>
                    <option value="512GB">512GB</option>
                    <option value="1TB">1TB</option>
                </select>        
            </div>

            <div class="item-addproduct">
                <label for="Mota">Mô tả</label>
                <textarea type="text" name="Mota" placeholder="Nhập mô tả cho sản phẩm."></textarea>
            </div>
        </div>
    </div>

    <div class="addproduct-form-btn">
        <button class="btn-cancel" name="Cancel" onclick="CloseAddProductPop()">Hủy</button>
        <button type="submit" class="add-SP" name="Add-SP" onclick="InsertProduct()">Thêm</button>
    </div>
</div>

<script type="text/javascript" src="../../js/admin/InsertReceipt.js"></script>