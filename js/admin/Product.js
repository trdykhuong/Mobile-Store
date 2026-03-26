const modal = document.getElementById("modal");
const formUpdate = document.getElementById("product-detail-form");
const listPr = new Array();

// Đơn vị tiền VND
function formatCurrencyVND(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount);
}


// click modal
document.addEventListener('click', (e)=> {
    if (modal.contains(e.target) 
        && !formUpdate.contains(e.target)) {
        modal.classList.remove("open-modal");
        CloseDetail()
        }
})

OnloadData = () => {
    LoadProducts();
    LoadData("Hang");
    LoadData("Danhmuc");
}

LoadStatus = () => {
    var trangthai = document.querySelector('input[name=trangthai]').value;
    console.log(trangthai);
    btn.innerText = parseInt(trangthai) == 1? "Khóa" : "Mở khóa";
}

//Load danh sách sp lên
LoadProducts = async(text, hang, danhmuc, giatu, giaden, order) => {
    const url = '../Controller/Product/ListProduct.php';
    let result = document.getElementById('result');

    const ListProduct = async() => {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    text: text,
                    hang: hang,
                    danhmuc: danhmuc,
                    giatu: giatu,
                    giaden: giaden,
                    order: order
                })
            })

            const data = response.json();
            return data;
        } catch(err) {
            console.error(err);
        }
    }

    const products = await ListProduct();
    if(products.length == 0){
        result.innerHTML = 'Không tìm thấy sản phẩm';
        return;
    }

    result.innerHTML = '';
    let rows = document.getElementById('productRows');
    rows.innerHTML = '';

    console.log(products);
    products.forEach(element => {
        rows.innerHTML +=`
        <tr class='Product-rows' onclick="OpenDetail(${element.id})">
            <input type='hidden' name='trangthai-rows' value=${element.trangthai}>
            <td>
                <div class='name-rows'>
                    <img class='imgSP' src='../../images/products/${element.img}' alt='${element.name}'>
                    <p>${element.name}</p>
                </div>
            </td>
            <td>${element.hang}</td>
            <td>${element.danhmuc}</td>
            <td>${formatCurrencyVND(element.gianhap)}</td>
            <td>${formatCurrencyVND(element.giaban)}</td>
            <td>${element.discount}%</td>
        </tr>`;
    });
}

//Filter
const modalProduct = document.getElementById("modal-product");
const filterForm = document.getElementById("second-filter");

document.addEventListener("click", function(event) {
    // const filter = document.getElementById("filter")
    if (event.target.closest(".btn-open-filter") || event.target.closest(".close-filter-form")) {
        return;
    }
    // if (filterForm.classList.contains("open-second-filter")) {
    //     closeFilterForm();
    // }
})

function openFilterForm() {
    filterForm.classList.add("open-second-filter");
}

function closeFilterForm() {
    filterForm.classList.remove("open-second-filter");
}
///Hết animetion

//phiu tơ code
const submitfilter = document.querySelector('button[name=filter]')
const search_filter = document.querySelector('input[name=search-filter]')

search_filter.addEventListener('input', ()=>{
    const text = document.querySelector('input[name=search-filter]').value
    console.log(text)
    //Clear table trc
    let rows = document.getElementById('productRows')
    rows.innerHTML = ''
    //Load lên
    LoadProducts(text, 0,0,0,0,1)
})

submitfilter.addEventListener('click', ()=>{
    const hang = document.querySelector('select[name=filter-Hang]')
    const danhmuc = document.querySelector('select[name=filter-Danhmuc]')
    const giatu = document.querySelector('input[name=gianhap-from]')
    const giaden = document.querySelector('input[name=gianhap-to]')
    const order = document.querySelector('select[name=orderby]')

    if(parseInt(giaden) < parseInt(giatu)){
        alert("Giá sau phải lớn hơn giá trước nha bà Thơ")
        return
    }
    
    let rows = document.getElementById('productRows')
    rows.innerHTML = ''
    LoadProducts('', hang.value, danhmuc.value, giatu.value, giaden.value, order.value)

    hang.value = 0
    danhmuc.value = 0
    giatu.value = 0
    giaden.value = 0
    order.value = 0
    closeFilterForm()
})

//hết phiu tơ
const btn = document.getElementById('re-btn')
Handle = async()=>{
    ///Input
    const text = btn.innerHTML
    let id = document.querySelector('p[name=maSP]').innerHTML.toString();
    const idctsp = id.slice(0, id.length)
    
    if(text=="Khóa") {
        if(!confirm("Bà chắc chưa bà Thơ ???")) return
    }
    
    ///
    const url = '../Controller/Product/HandleProduct.php'
    const HandleProduct = async()=>{
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idctsp: idctsp,
                    text: text
                })
            })

            const data = response.json()
            return data
        } catch (error) {
            console.error(error)
        }
    }

    const result = await HandleProduct()
    alert(result.message) //Thông báo
    //Set lại nút handle
    document.querySelector('input[name=trangthai]').value = result.trangthaimoi
    LoadStatus()
}

//Cập nhật sản phửm
document.getElementById('update').addEventListener('click', async ()=>{
    let id = document.querySelector('p[name=maSP]').innerHTML.toString()
    //Chuẩn bị data để update
    const idctsp = id;
    const hang = document.getElementById('Hang-updt').value
    const danhmuc = document.getElementById('Danhmuc-updt').value
    const listCD = document.getElementById('variant-title').value
    const [mausac, dungluong] = listCD.split('|');
    const mota = document.querySelector('textarea[name=Mota-updt]').textContent
    const giamgia = document.querySelector('input[name=GiamGia-updt]').value
    const img = document.querySelector('input[name=Img]')
    ///File xử lý
    const url = '../Controller/Product/UpdateProduct.php'
    //Check input
    var file = img.files[0];

    if (file) {
        const reader = new FileReader();
        
        reader.onloadend = async()=> {
            const base64Data = reader.result.split(',')[1]; 

        const updateData = async()=>{
            try{
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        idctsp: idctsp,
                        hang: hang,
                        danhmuc: danhmuc,
                        mausac: mausac,
                        dungluong: dungluong,
                        mota: mota,
                        giamgia: giamgia,
                        fileName: file.name,
                        fileType: file.type,
                        fileData: base64Data
                    })
                })

                const data = response.json()
                return data
            } catch(err){
                console.error(err)
            }
        }
        const message = await updateData()
        alert(message.message)
    }
    
    reader.readAsDataURL(file);  // Đọc file dưới dạng base64

    }else{
        const updateData = async()=>{
            try{
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        idctsp: idctsp,
                        hang: hang,
                        dungluong: dungluong,
                        mausac: mausac,
                        danhmuc: danhmuc,
                        mota: mota,
                        giamgia: giamgia
                    })
                })

                const data = response.json()
                return data
            } catch(err){
                console.error(err)
            }
        }
// -
        const message = await updateData()
        alert(message.message)
    }
})
//Hàm hiện ảnh khi đổi
hienThiAnh = (event, type)=> {
    var input = event.target;
    var reader = new FileReader();
    reader.onload = function(){
        var dataURL = reader.result;
        var img = document.getElementById("img-"+type);
        img.src = dataURL;  
    };
    reader.readAsDataURL(input.files[0]);
}
//Loaddata
LoadData = async(type)=>{
    const url = '../Controller/Product/LoadData.php?type=' + type
    let result = document.querySelector('select[name=' + type + ']')
    let result_filter = document.querySelector('select[name=filter-' + type + ']')

    const ListData = async() =>{
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                'Content-Type': 'application/json'
            }
            })

            const data = response.json()
            return data
        } catch (err) {
            console.error(err)  
        }
    }
    const list = await ListData()
    list.forEach(element =>{
        result.innerHTML += `<option value='${element.id}'>${element.name}</option>`
        result_filter.innerHTML += `<option value='${element.id}'>${element.name}</option>`
    }) 
}


//Chỗ này đang lỗi xíu nhớ sửa lại
loadDataProduct = async (idSP) => {
    const url = '../Controller/Product/LoadTypeProduct.php?idSP=' + idSP;

    const variantSelect = document.querySelector('select[name=variant-title]');

    const ListData = async () => {
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();  // await ở đây!
            return data;
        } catch (err) {
            console.error(err);
        }
    };

    const list = await ListData();

    variantSelect.innerHTML = "";

    const variantSet = new Set();

    list.forEach(e => {
        const value = `${e.mausac}|${e.dungluong}`;
        const text = `${e.mausac} - ${e.dungluong}`;

        if (!variantSet.has(value)) {
            variantSet.add(value);
            variantSelect.innerHTML += `<option value="${value}">${text}</option>`;
        }
    });
};


document.getElementById('variant-title').addEventListener('change', function() {
    const [mausac, dungluong] = this.value.split('|');

    let selectedDetail = null;

    listPr.forEach(element => {
        if (element.mausac === mausac && element.dungluong === dungluong) {
            selectedDetail = element;
        }
    });

    if (selectedDetail) {
        loadDetailProduct(selectedDetail);
    }
});



UpdateProduct = async (idSP)=>{
    const url = '../Controller/Product/ShowDetail.php?idSP=' + idSP
    console.log(idSP)
    const loadDetail = async()=>{
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                'Content-Type': 'application/json'
            }
            })

            const data = response.json()
            return data
        } catch (error) {
            console.error(err)
        }
    }
    const detail = await loadDetail()
    detail.forEach(element => {
        listPr.push(element);
        console.log(element);
    });
    
    loadDetailProduct(detail[0])
    document.getElementById('Hang-updt').selectedIndex = detail[0].hang - 1;
    console.log("danhmuc " + detail.dm);
    document.getElementById('Danhmuc-updt').selectedIndex = detail[0].dm - 1;
    LoadStatus()
}


function loadDetailProduct(detail) {
    document.querySelector('p[name=maSP]').innerHTML = detail.idCTSP;
    document.querySelector('p[name=Tensp-updt]').innerHTML = detail.tensp;
    // document.querySelector('p[name=soluong]').innerHTML = detail.soluong + ' cái'
    document.querySelector('p[name=gianhap]').innerHTML = formatCurrencyVND(detail.gianhap) + ' VNĐ';
    if(detail.giathem == "NULL") console.log(detail.giathem)
    document.querySelector('p[name=giathem]').innerHTML = formatCurrencyVND(detail.giathem) + ' VNĐ';
    document.querySelector('p[name=giaban]').innerHTML = formatCurrencyVND(detail.giaban) + ' VNĐ';
    const loinhuan = parseInt(parseFloat(parseFloat(detail.giaban)/parseFloat(detail.gianhap)) * 100) - 100;
    document.querySelector('p[name=loinhuan]').innerHTML = loinhuan +"%";
    document.querySelector('input[name=GiamGia-updt]').value = detail.discount;
    document.querySelector('textarea[name=Mota-updt]').textContent = detail.mota;
    document.getElementById('img-updt').src = '../../images/products/' + detail.img;
    document.querySelector('input[name=trangthai]').value = detail.trangthai;
}

OpenDetail = async(idsp)=>{
    await UpdateProduct(idsp)
    await loadDataProduct(idsp)
    modal.classList.add("open-modal");
    formUpdate.classList.add("open-product-detail");
    document.querySelector(".hidden-log-out").classList.add("active");
}

CloseDetail = ()=>{
    modal.classList.remove("open-modal");
    formUpdate.classList.remove("open-product-detail");
    document.querySelector(".hidden-log-out").classList.remove("active");
}