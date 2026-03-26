window.onbeforeunload = function(event) {
    // ev.preventDefault();
    return "Thay đổi sẽ không được lưu";
}

const ncc_select = document.getElementById('input-ncc');
const sp_select = document.getElementById('input-listSP');
let scrollViewSP = document.getElementById('scrollView-SP');

let products = new Array();
let gianhap = document.getElementById('input-gianhap');

let str = '';
let thanhtien_value = 0;
let loinhuan = document.getElementById('input-loinhuan');
let inputquantity = document.getElementById('input-soluong');
let giaCongThem = document.getElementById('input-congthem');
let thanhtienchan = document.getElementById('thanhtien-sp');

//Liên quan đến thêm sản phẩm
let addproduct = document.getElementById('addproduct-container');

const tensp = document.querySelector('input[name=Tensp]');
const hang_select = document.querySelector('select[name=hang]');
const danhmuc_select = document.querySelector('select[name=danhmuc]');
const mausac = document.querySelector('input[name=mausac]');
const dungluong_select = document.querySelector('select[name=dl]');
const mota = document.querySelector('textarea[name=Mota]')
const img = document.querySelector('input[name=Img]');

const listPr = new Array();

//Thim sản phữm mới
function OpenAddProductPop(){
    addproduct.classList.add("open-addproduct");
    document.querySelector(".hidden-log-out").classList.add("active");
}

function CloseAddProductPop(){
    addproduct.classList.remove("open-addproduct");
    document.querySelector(".hidden-log-out").classList.remove("active");
}

//Hiện ảnh
function hienThiAnh(event) {
    var input = event.target;
    var reader = new FileReader();
    reader.onload = function(){
        var dataURL = reader.result;
        var img = document.getElementById("img");
        img.src = dataURL;  
    };
    var dataimg = new FormData();
    dataimg.append('file', img.files[0]);
    console.log(img.files[0]);
    reader.readAsDataURL(input.files[0]);
}

//Lấy thông tin rồi thim sản phẩm thoai
function InsertProduct(){
    if(tensp.value == ''){
        alert("Chưa nhập tên sản phẩm ấy ơi !"); return;
    }

    if(hang_select.value <= 0 ){
        alert("Chưa chọn hãng cho sản phẩm ấy ơi !"); return;
    }

    if(danhmuc_select.value <= 0 ){
        alert("Chưa chọn danh mục cho sản phẩm ấy ơi !"); return;
    }

    if(mausac.value == '' && 
        (hang_select.value == 1 || hang_select.value == 7 || hang_select.value == 8) ){
        alert("Chưa nhập màu sắc cho sản phẩm ấy ơi !"); return;
    }

    if(img.files.length <= 0 ){
        alert("Chưa có hình nè"); return;
    }

    const url = '../Controller/Receipt/InsertProduct.php';      
    var file = img.files[0];
    // console.log(file);
    var mausacIns = '';
    if(mausac.value == '') {
        mausacIns = "KHÔNG CÓ";
    } else {
        mausacIns = mausac.value;
    }

    console.log("dữ liệu muốn sem: " + dungluong_select.value + " và " + mausacIns);

    if (file) {
        const reader = new FileReader();
        
        reader.onloadend = function() {
            const base64Data = reader.result.split(',')[1]; 

            try{
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        tensp: tensp.value,
                        hang: hang_select.value,
                        danhmuc: danhmuc_select.value,
                        mausac: mausacIns,
                        dungluong: dungluong_select.value,
                        mota: mota.value,
                        fileName: file.name,
                        fileType: file.type,
                        fileData: base64Data
                })
                })
                .then(response => response.json())
                .then(data =>{
                    alert(data.message);
                    if(data.status != false){

                        addproduct.classList.remove("open-addproduct");
                        document.querySelector(".hidden-log-out").classList.remove("active");
                        ClearAddProduct()
                        sp_select.innerHTML = "";
                        DisplaySelect('SP');
                    }
                })
                .catch(err => console.error(err))
            }catch(error){
                console.error(error)
            }    
        };
        reader.readAsDataURL(file);  // Đọc file dưới dạng base64
    }
}

ClearAddProduct = () =>{
    tensp.value = ''
    hang_select.value = 0
    danhmuc_select.value = 0
    mausac.value = ''
    dungluong_select.value = 0
    mota.value = 0
    img.value = ""
    document.getElementById("color_displ").style.display = "none";
    document.getElementById("dl_displ").style.display = "none";
}

//Sau khi insert thì load lại
function LoadData(){
    DisplaySelect('NCC');
    DisplaySelect('SP');
    DisplaySelect('HANG');
    DisplaySelect('DANHMUC');
}

function DisplaySelect(type){
    var str = '';
    var url = '../Controller/Receipt/LoadDataToInsert.php?type=' + type;
    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        str += type=='NCC'? `<option value="0">---Chọn nhà cung cấp---</option>`: 
        (type=='HANG'? `<option value="0">---Chọn hãng---</option>`:
        (type=='DANHMUC'? `<option value="0">---Chọn danh mục---</option>` : `<option value="0">---Chọn sản phẩm---</option>`));
        data.forEach(element => {
            str += `<option value="${element.id}">${element.name}</option>`
        });

        if(type=='NCC'){
            ncc_select.innerHTML += str;
        }else if(type=='SP'){
            sp_select.innerHTML += str;
        }else if(type=='HANG'){
            hang_select.innerHTML += str;
        }
        else{
            danhmuc_select.innerHTML += str;
        }
    })
}

document.getElementById("danhmuc").addEventListener('change', () =>{
    console.log("hong dô dây hã mom")
    if(danhmuc_select.value == 1 || danhmuc_select.value == 7 || danhmuc_select.value == 8) {
        document.getElementById("color_displ").style = "display: flex; justify-content: space-between; align-items: center;";
        document.getElementById("dl_displ").style = "display: flex; justify-content: space-between; align-items: center;";
    } else if(danhmuc_select.value == 3 || danhmuc_select.value == 5 || danhmuc_select.value == 2) {
        document.getElementById("color_displ").style.display = "block";
        document.getElementById("dl_displ").style.display = "none";
    } else {
        document.getElementById("color_displ").style.display = "none";
        document.getElementById("dl_displ").style.display = "none";
    }
})

//Này là ấy 
function Return(){
    // alert("Từ từ chưa viết đi~ ơi");
    document.getElementById('addReceipt-popup').classList.remove('open-addreceipt');
}
//////

//Khúc dưới này là của khúc trên
function Resetinput(){
    products.splice(0, products.length);
    str = str.replace(str, '');
    ncc_select.value = 0;
    sp_select.value = 0;
    scrollViewSP.innerHTML = str;
    loinhuan.value = 1;
    inputquantity.value = 1;
    gianhap.value = '';
    thanhtien_value = 0;
    thanhtienchan.innerHTML = parseInt(thanhtien_value);

}

function OnchangeLoiNhuan() {
    var loinhuanchange = document.getElementById('input-loinhuan').value;

    // Reset lại hiển thị và tổng tiền
    str = '';
    scrollViewSP.innerHTML = '';
    thanhtien_value = 0;

    products.forEach(element => {
        DisplayProduct(element[0], element[1], element[2], element[3], element[4], element[5], loinhuanchange);
    });

    alert("Cập nhật lợi nhuận thành công");
}


function InsertReceipt(){
    if(ncc_select.value == 0){
        alert('Chọn nhà cung cấp đi');
        return;
    }

    if(products.length <= 0){
        alert('Thim sản phửm dùm con');
        return;
    }

    try{
    const url = '../Controller/Receipt/InsertReceipt.php';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            idNCC: ncc_select.value,
            loinhuan: loinhuan.value,
            thanhtien: thanhtien_value
        })
        })
        .then(response => response.json())
        .then(data => {
            var idPN = data.id;
            var loinhuan = data.loinhuan;

            products.forEach(element => {
                console.log(element);
                InsertReceiptDetail(idPN, element[0], element[1], element[2], element[3], element[4], element[5], loinhuan);
            })
            alert("Insert phíu nhoặp rầu nghen");
            Resetinput();
            modal.classList.remove("open-modal");
            addreceipt.classList.remove("open-addreceipt");
            document.querySelector(".hidden-log-out").classList.remove("active");
            sp_select.innerHTML = '';
            DisplaySelect('SP');

            // table.innerHTML = '';
            LoadReceipt();

            console.log("select nè: ");
            

        })
    }catch(error){
        console.error(error)
    }
}

function InsertReceiptDetail(idPN, idSP, soluongSP, gianhapSP, giathem, mausac, dungluong, loinhuan){
    try{
    const url = '../Controller/Receipt/InsertReceiptDetail.php';

    console.log(idPN + ", " + idSP + ", " + mausac + ", " + dungluong + ", ");
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            idPN: idPN,
            idSP: idSP,
            soluong: soluongSP,
            gianhap: gianhapSP,
            giathem: giathem,
            mausac: mausac,
            dungluong: dungluong,
            loinhuan: loinhuan
        })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data.message);
        })
    }catch(error){
        console.error(error)
    }
}

function Display(){
    var url = '../../admin/Controller/Receipt/LoadProduct.php';
    console.log("idsp = " + sp_select.value);
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            idSP: sp_select.value
        })
    })
    .then(response => response.json())
    .then(data=> {
        document.getElementById("co-bl").style.display = "none";
        document.getElementById("dl-bl").style.display = "none";
        document.getElementById("priceAdd").style.display = "none";
        gianhap.value = data[0].gianhap; 
        console.log("gianhap "+ gianhap.value);
        let hasMauSac = false;
        let hasDungLuong = false;

        data.forEach(sp => {
            if (sp.mausac !== "KHÔNG CÓ") hasMauSac = true;
            if (sp.dungluong !== "KHÔNG CÓ") hasDungLuong = true;
        });

        document.getElementById("co-bl").style.display = hasMauSac ? "block" : "none";
        document.getElementById("dl-bl").style.display = hasDungLuong ? "block" : "none";
        document.getElementById("priceAdd").style.display = (hasMauSac || hasDungLuong) ? "block" : "none";


        const colors = new Set();
        const dungluongs = new Set();
        data.forEach(e => {
            listPr.push(e);
            colors.add(e.mausac);
            dungluongs.add(e.dungluong);
        })

        document.getElementById("input-listC").innerHTML = "";
        document.getElementById("input-listR").innerHTML = "";

        colors.forEach(c => {
            document.getElementById("input-listC").innerHTML += `<option value="${c}">${c}</option>`;
        })
        dungluongs.forEach(d => {
            document.getElementById("input-listR").innerHTML += `<option value="${d}" >${d}</option>`;
        })
        loadListPro(document.getElementById("input-listC").value, document.getElementById("input-listR").value);
    })
    .catch(err => console.log(err))
}

document.getElementById("input-listC").addEventListener('change', () => {
    const mausac = document.getElementById("input-listC").value;
    const listR = document.getElementById("input-listR");

    listR.innerHTML = "";

    let firstDungLuong = "";

    listPr.forEach(d => {
        if (d.mausac == mausac) {
            listR.innerHTML += `<option value="${d.dungluong}">${d.dungluong}</option>`;
            if (firstDungLuong === "") {
                firstDungLuong = d.dungluong;
            }
        }
    });

    loadListPro(mausac, firstDungLuong);
});

document.getElementById("input-listR").addEventListener('change', () => {
    const dungluong = document.getElementById("input-listR").value;
    const listC = document.getElementById("input-listC");

    listC.innerHTML = "";

    let firstMausac = "";

    listPr.forEach(d => {
        if (d.dungluong == dungluong) {
            listC.innerHTML += `<option value="${d.mausac}">${d.mausac}</option>`;
            if (firstMausac === "") {
                firstMausac = d.mausac;
            }
        }
    });

    loadListPro(firstMausac, dungluong);
});


function loadListPro(mausac, dungluong) {
    document.getElementById("input-congthem").value = "";
    gianhap.value = "";

    listPr.forEach(e => {
        if (e.mausac == mausac && e.dungluong == dungluong) {
            document.getElementById("input-congthem").value = e.giathem;
            gianhap.value = e.gianhap;
        }
    });
}


function AddProduct(){
    let idSP = document.getElementById('input-listSP').value;
    let quantity = document.getElementById('input-soluong').value;

    let mausac = document.getElementById('input-listC').value;
    let dungluong = document.getElementById('input-listR').value;
    console.log(mausac + "   , " + dungluong)
    // console.log(123123)
    console.log("idsp= " + idSP);
    console.log(products)
    if(idSP<=0 ){
        alert('Hãy chọn sản phẩm!');
        return;
    }

    var exist = products.filter(item =>
        item[0] == idSP && item[4] == mausac && item[5] == dungluong
    );
    console.log(exist)

    if(exist.length > 0){
        console.log(parseInt(exist[0][2]) + " = " + gianhap.value)
        if(parseInt(exist[0][2]) != gianhap.value){
            RemoveProduct(exist[0][0])
            var product = new Array(idSP, quantity, gianhap.value, giaCongThem.value, mausac, dungluong);
            products.push(product);

            // alert("Thêm sản phẩm thành công!");
            DisplayProduct(product[0], product[1], product[2], product[3], product[4], product[5], loinhuan.value);
            return;
        }
        alert("Sản phửm đã có");
        console.log(idSP);
        return;
    }

    if(!Number.isInteger(parseInt(loinhuan.value)) || parseInt(loinhuan.value) > 99 || parseInt(loinhuan.value) < 0){
        alert("Lợi nhuận là số từ 0-99 á má");
        return;
    }

    if(!Number.isInteger(parseInt(gianhap.value)) || parseInt(gianhap.value) < 0){
        alert("Giá nhập là số lớn hơn 0 á má");
        return;
    }
    if(!Number.isInteger(parseInt(inputquantity.value)) || parseInt(inputquantity.value) < 0){
        alert("Số lượng là số lớn hơn 0 á má");
        return;
    }

    if(!Number.isInteger(parseInt(giaCongThem.value)) || parseInt(giaCongThem.value) < 0){
        alert("Thêm ko thêm còn đòi âm nữa");
        return;
    }

    var product = new Array(idSP, quantity, gianhap.value, giaCongThem.value, mausac, dungluong);
    products.push(product);

    alert("Thêm sản phẩm thành công!");
    DisplayProduct(product[0], product[1], product[2], product[3], product[4], product[5], loinhuan.value);
}

function RemoveProduct(idsp){
    
    // let idsp = document.getElementById('input-id').value;
    console.log("Remove: " + idsp);
    var isExisted = products.filter(item => item[0]==idsp);
    isExisted_quantity = isExisted[0][1];
    isExisted_gianhapsp = isExisted[0][2];
    isExisted_giathem = isExisted[0][3]
    console.log(isExisted_gianhapsp)
    products.splice(products.indexOf(isExisted[0]), 1);
    alert("Xóa thành công");
    
    str = str.replace(str, '');
    scrollViewSP.innerHTML = str;
    thanhtien_value -= parseInt(isExisted_quantity) * (parseInt(isExisted_gianhapsp) + parseInt(isExisted_giathem));
    console.log("thanh tien sau xoa: " + thanhtien_value);
    thanhtienchan.innerHTML = parseInt(thanhtien_value) + ' VND';

    if(products.length == 0){
        return;
    }
    products.forEach(element =>{
        DisplayProduct(element[0], element[1], element[2], element[3] , element[4], element[5], loinhuan.value);
    })

}

function DisplayProduct(idsp, quantity, gianhapsp, giathem, mausac, dungluong, loinhuansp){   
    var url = '../Controller/Receipt/LoadProductDetail.php';
    // console.log("123cmm")
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            idSP: idsp,
            mausac: mausac,
            dungluong: dungluong
        }) 
    })
    .then(response => response.json())
    .then(data => {
        // str = str.replace(str, '');
        // scrollViewSP.innerHTML = str;

        str +=`
            <div class='item'>
                <img src="../../images/products/${data.img}" class='img-product'>
                <div class='item-detail-product'>
                    <div class='item-detail-product-1'>
                        <p>${data.name}</p>
                        <div class='item-detail-product-1-1'>
                            <span>${data.mausac}</span>
                            <span>${data.dungluong}</span>
                            <span>+${giathem} VND</span>
                        </div>
                        <div class='item-detail-product-1-2'>
                            <span>Giá cũ: ${data.gianhap} VND</span>
                            <span>Giá mới: ${parseInt(gianhapsp) + parseInt(giathem)} VND</span>
                        </div>  
                    </div>

                    <div class='item-detail-product-2'>
                        <p>
                            <strong>Lợi nhuận</strong><br>
                            ${loinhuansp}%
                        </p>
                    </div>

                    <div class='item-detail-product-3'>
                        <div class='action-item'>
                            <button onclick="Action('-', ${data.id})">-</button>
                            <p id='quantity-${data.id}'>${quantity}</p>
                            <button onclick="Action('+', ${data.id})">+</button>
                        </div>
                        <button class='btn-delete' onclick="RemoveProduct(${data.id})">Xóa</button>
                    </div>
                </div>
            </div>`;

        scrollViewSP.innerHTML = str;
        console.log(products)
        thanhtien_value += parseInt(quantity) * (parseInt(gianhapsp) + parseInt(giathem));
        thanhtienchan.innerHTML = parseInt(thanhtien_value)  + ' VND';
    })
}

function Action(str, idsp){
    var quantitysp = document.getElementById('quantity-'+idsp);

    if(str == "+"){
        products.forEach(element => {
            if(element[0] == idsp){
                element[1] = parseInt(element[1]) + 1;
                quantitysp.innerHTML = element[1];

                thanhtien_value += (parseInt(element[2]) + parseInt(element[3]));
                thanhtienchan.innerHTML = parseInt(thanhtien_value)  + ' VND';
            }
        });
    }else{
        products.forEach(element => {
            if(element[0] == idsp){
                if((parseInt(element[1]) - 1) < 1){
                    alert('Xóa mẹ luôn đi');
                    return;
                }
                element[1] = parseInt(element[1]) - 1;
                quantitysp.innerHTML = element[1];

                thanhtien_value -= (parseInt(element[2]) + parseInt(element[3]));
                thanhtienchan.innerHTML = parseInt(thanhtien_value)  + ' VND';
            }
        });
    }
}

// Đơn vị tiền VND
function formatCurrencyVND(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount);
}
