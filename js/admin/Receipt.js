// Đơn vị tiền VND
function formatCurrencyVND(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount);
}

function OpenDetail(clicked_id){
    OpenReceiptDetailPop();
    const hangSP = document.getElementById('hang-sp');
    const nccElement = document.getElementById('ncc-name');
    const ngaynhapElement = document.getElementById('ngay-nhap');
    var str = '';

    const row = document.getElementById('tr-' + clicked_id);
    const ncc = row.querySelector('#NCC').innerText; 
    const ngaynhap = row.querySelector('#ngaynhap').innerText; 

    nccElement.innerText = ncc;
    ngaynhapElement.innerText = ngaynhap;

    // console.log(ngaynghi, lydo);
    const url = "../Controller/Receipt/ShowReceiptDetail.php?idPN=" + clicked_id;
    console.log(clicked_id);
    try {
        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log("efbe" + data);
            data.forEach(element =>{
                console.log(element.img);
                str += `<tr class='SP-rows'">
                    <td><div class='name-sp'>
                        <img class='img-sp' src="../../images/products/${element.img}">
                        <p>${element.tensp}</p>
                    </div></td>
                    <td>${element.mausac}</td>
                    <td>${element.dungluong}</td>
                    <td>${formatCurrencyVND(element.gianhap)}</td>
                    <td>${formatCurrencyVND(element.giaban)}</td>
                    <td>${element.soluong}</td>
                    </tr>`;
            })
    
            hangSP.innerHTML = str;
            document.getElementById('maPN').innerText = ' PN' + clicked_id;
        })
        .catch(error => console.error('Error:', error))
    } catch (error) {
        console.log(error);
    }
    
}


function LoadReceipt(){
    // console.log("dô r");
    const table = document.getElementById('hang');
    let keyword = document.getElementById('keyword').value;
    let order = document.getElementById('order-price').value;
    let dateSearch = document.getElementById('date-serch').value;
    // console.log(dateSearch);
   
    try{
        var str = '';
        // url = 
        fetch('../Controller/Receipt/LoadReceipt.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                keyword: keyword,
                order : order,
                dateSearch : dateSearch
            })
            })
            .then(response => response.json())
            .then(data =>{
                if(data.length > 0){
                    data.forEach(element => {
                        // console.log(element.heso, element.ngaylam);
                        str +=`<tr class='PN-rows' id='tr-${element.id}' onmouseenter="ShowDetail(${element.id})" onclick="OpenDetail(${element.id})">
                                <td id="idPN">PN${element.id}</td>
                                <td id="NCC">${element.ncc}</td>
                                <td id="diachi">${element.diachi}</td>
                                <td id="ngaynhap">${element.ngaynhap}</td>
                                <td id="thanhtien">${formatCurrencyVND(element.thanhtien)} VND</td>
                                <td id="loinhuan">${element.loinhuan}%</td>
                                </tr>`;
                    });
                    table.innerHTML = str;
                    document.getElementById("result").innerHTML = "";
                }else{
                    table.innerHTML = '';
                    document.getElementById("result").innerHTML = "Không tìm thấy phiếu nhập nào đi~ ơi";
                }
                }
            )
            .catch(error => console.error(error))

        }catch(error){
            console.error(error)
        }
}

function ShowDetail(id){

    var btn = document.getElementById('tr-' + id);
    var str = '';
    // btn.title = "id: " + id; 
    // console.log(btn);

    try{
        url = '../Controller/Receipt/LoadReceiptDetail.php';
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                idPN : id
            })
        })
        .then(response => response.json())
        .then(data =>{
            data.forEach(element =>{
                str += element.tensp + ': ' + element.soluong + ' cái\n';
            })

            btn.title = str;
        })
        .catch(error => console.error(error));

    }catch(error) {
        console.error(error);
    }
}

const addreceipt = document.getElementById('addReceipt-popup');

document.addEventListener('click', (e)=> {
    if (modal.contains(e.target) 
        && !addreceipt.contains(e.target) 
        && !receiptdetail.contains(e.target)) {
            modal.classList.remove("open-modal");
            CloseAddReceiptPop();
            closeReceiptDetailPop();
    }
})


function OpenAddReceiptPop(){
    modal.classList.add("open-modal");
    addreceipt.classList.add("open-addreceipt");
    document.querySelector(".hidden-log-out").classList.add("active");
}

function CloseAddReceiptPop(){
    modal.classList.remove("open-modal");
    addreceipt.classList.remove("open-addreceipt");
    document.querySelector(".hidden-log-out").classList.remove("active");
}

//Mở trang chi tiết phiếu nhập
const receiptdetail = document.getElementById('receiptDetail-form');
const modal = document.getElementById('modal');

function OpenReceiptDetailPop(){
    modal.classList.add('open-modal');
    receiptdetail.classList.add("open-detail");
    document.querySelector(".hidden-log-out").classList.add("active");
}

function closeReceiptDetailPop(){
    modal.classList.remove('open-modal');
    receiptdetail.classList.remove("open-detail");
    document.querySelector(".hidden-log-out").classList.remove("active");
}


function OnloadData(){
    LoadData();
    LoadReceipt();
}

function LoadData(){
    DisplaySelect('NCC');
    DisplaySelect('SP');
    DisplaySelect('HANG');
    DisplaySelect('DANHMUC');
}