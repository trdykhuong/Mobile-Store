const modal = document.getElementById('modal');
const formAddInvoice = document.getElementById('add-invoice');

function openAddInvoice () {
    modal.classList.add('open-modal');
    formAddInvoice.classList.add('open-add-invoice');
    document.querySelector(".hidden-log-out").classList.add("active");
}

function closeAddInvoice () {
    modal.classList.remove('open-modal');
    formAddInvoice.classList.remove('open-add-invoice');
    document.querySelector(".hidden-log-out").classList.remove("active");
}
///Tải sản phẩm cho ngta chọn
LoadProduct()

function LoadProduct(){
    DisplaySelect()
}

async function DisplaySelect(){
    const productSelect = document.getElementById('ProductSelect')
    const url = '../Controller/order/ProductList.php';

    const ProductList = async ()=>{
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
    
            const data = response.json();
            return data; 
        } catch (error) {
            console.error(error);
        }
    }

    const productList = await ProductList();

    productList.forEach(element => {
        productSelect.innerHTML += `<option value="${element.id}">${element.name}</option>`
    });
}

function RemoveProduct(idsp){
    
    // let idsp = document.getElementById('input-id').value;
    console.log("Remove: " + idsp);
    var isExisted = products.filter(item => item[0]==idsp);
    isExisted_quantity = isExisted[0][1];
    isExisted_gianhapsp = isExisted[0][2];
    products.splice(products.indexOf(isExisted[0]), 1);
    alert("Xóa thành công");
    
    str = str.replace(str, '');
    scrollViewSP.innerHTML = str;
    thanhtien_value -= parseInt(isExisted_quantity) * parseInt(isExisted_gianhapsp);
    console.log("thanh tien sau xoa: " + thanhtien_value);
    thanhtienchan.innerHTML = parseInt(thanhtien_value) + ' VND';

    if(products.length == 0){
        return;
    }
    products.forEach(element =>{
        DisplayProduct(element[0], element[1], element[2], loinhuan.value);
    })

}

function DisplayProduct(idsp, quantity, gianhapsp, loinhuansp){   
    var url = '../Controller/Receipt/LoadProduct.php?idSP=' + idsp;
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        str +=`<div class='item'>
                <img src="/images/products/${data.img}">
                <p>${data.name}</p>
                <p>Giá cũ: ${data.gianhap} Vnd</p>
                <p>Giá mới: ${parseInt(gianhapsp)} Vnd</p>
                <p>Lời: ${loinhuansp}%</p>
                <div class='action-item'>
                    <button onclick="Action('-', ${data.id})">-</button>
                    <p id='quantity-${data.id}'>${quantity}</p>
                    <button onclick="Action('+', ${data.id})">+</button>
                </div>
             <button class='btn-delete' onclick="RemoveProduct(${data.id})">Xóa</button>
            </div>`;

        scrollViewSP.innerHTML = str;

        thanhtien_value += parseInt(quantity) * parseFloat(gianhapsp);
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

                thanhtien_value += parseInt(element[2]);
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

                thanhtien_value -= parseFloat(element[2]);
                thanhtienchan.innerHTML = parseInt(thanhtien_value)  + ' VND';
            }
        });
    }
}

function InsertReceiptDetail(idPN, idSP, soluongSP, gianhapSP, loinhuan){
    try{
    const url = '../Controller/Receipt/InsertReceiptDetail.php';

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
///////////////////////

// Đơn vị tiền VND
function formatCurrencyVND(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount) + ' VND';
}

function loadOrders(status = "all") {
fetch("../Controller/order/order.php?status=" + status)
    .then(response => response.json())
    .then(orders => {
        let html = `
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>

            <tbody>`;
                orders.forEach(order => {
                    html += `<tr>
                        <td>${order.idHD}</td>
                        <td>${order.khachhang}</td>
                        <td>${order.NGAYMUA}</td>
                        <td>${formatCurrencyVND(order.THANHTIEN)}</td>
                        <td>${order.STATUS}</td>
                        <td>
                            <a href='?page=orderdetail&idHD=${order.idHD}' class='btn-detail'>Chi tiết</a>
                        </td>
                    </tr>`;
                });
                html += `
            </tbody>
        </table>`;
        document.getElementById("orderList").innerHTML = html;
    })
    .catch(error => console.error("Lỗi tải đơn hàng:", error));
}

document.getElementById("filterStatus").addEventListener("change", function () {
    loadOrders(this.value);
});

window.onload = function() {
    loadOrders();
};