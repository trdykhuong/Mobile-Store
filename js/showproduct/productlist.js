let hanglist = new Array();
let dmlist = new Array();
let gialist = new Array();
let str = '';
let order_str = '';
let giamin = document.getElementById('price-min');
let giamax = document.getElementById('price-max');

//ấy sp lên
let productgrid = document.getElementById('product-grid');

//Cho phăn trang
let pagination = document.getElementById('paginationUl');
let currentPage = 1;
let productsPerPage = 15
let offset = 0;
let endpage = 0;
//cho phăn trang

// sp theo input vs select mở cmt dòng 22-26 và 310 đồng thời cmt dòng 15 và 309 lại
// Mở cmt 109 - 114 trong html
// let productsPerPage = parseInt(document.getElementById('number-product').value) || 15;
// document.getElementById('number-product').addEventListener('change', function() {
//     productsPerPage = parseInt(this.value) || 15;
//     Filter();
// })

//Ấy cái giá 
function formatPrice(price){
    var formattedprice = price.toLocaleString("vi-VN");
    return `${formattedprice}`;
}

function formatVND(input) {
    let value = input.value.replace(/\D/g, '');
    input.value = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
}

//Ấy cái giá 

//Ấy data lên
LoadWithFilter() //Gọi hàm

function LoadWithFilter(){
    var index = document.getElementById("DM").value;
    if(index > 0) dmlist.push(index)

    LoadBrand();
    LoadCagetory();
    filter_All();
    dmlist.splice(0, dmlist.length)
}

async function LoadBrand6(){
    try{
        const url = '../../Controller/productdetail/LoadData.php?type=brand6' 
        let str = '';

        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })

        const data = await response.json()
        var count = 0;
        data.forEach(element => {
            str += `<span class='brands-option' data-brand='${element.idtype}'>${element.typename}</span>`;
            count += 1;
        })
        
        if(count == data.length){
            str += `<p class="delete-choice-brands">Xóa tất cả</p>`;
        }
        document.getElementById('filter-brand-choice').innerHTML = str;

    }catch(err){
        console.error(err)
    }
}

async function LoadBrand(){
    try{
        const url = '../../Controller/productdetail/LoadData.php?type=brand' 
        var str = '';

        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })

        const data = await response.json();
        data.forEach(element =>{
            str += `<span data-brand='${element.idtype}' id='Hang-${element.idtype}' onclick='filter_Hang(${element.idtype})'
            class='Hang-${element.idtype}'>${element.typename}</span>`;
        })
        document.getElementById('brands-choice').innerHTML = str;

    }catch(err){
        console.error(err)
    }
}

async function LoadCagetory(){
    try{
        const url = '../../Controller/productdetail/LoadData.php?type=cagetory'
        var str = '';
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })

        const data = await response.json();
        data.forEach(element =>{
            str += `<span data-category='${element.idtype}' id='DM-${element.idtype}' onclick='filter_DM(${element.idtype})' 
            class='DM-${element.idtype}'>${element.typename}</span>`;
        })
        document.getElementById('category-choice').innerHTML = str;

    }catch(err){
        console.log(err);
    }

}
////////////////////////////////

//Filter nè
function Filter(){   
    offset = 0
    currentPage = 1  
    filter_All() 
    endpage = 0
    pagination.innerHTML = ''
    // LoadPage(currentPage)
}
    ///Order 
    function orderClick(thisText) {
        const allOrderSpans = document.querySelectorAll('.order-option');
        allOrderSpans.forEach(span => span.classList.remove('active-order'));

        thisText.classList.add('active-order');

        order_str = order_str.replace(order_str, ' GROUP BY sp.idSP ');
        switch(thisText.innerText){
            case 'Bán chạy': order_str +=` ORDER BY SOLUONG DESC` ;break;
            case 'Giảm giá': order_str +=`  ORDER BY sp.DISCOUNT DESC`;break;
            case 'Mới nhất': order_str +=` ORDER BY sp.idSP DESC`;break;
            case 'Giá thấp - cao': order_str +=` ORDER BY sp.GIA ASC`;break;
            case 'Giá cao - thấp': order_str +=` ORDER BY sp.GIA DESC`;break;
            default: break;
        }
             
        filter_All();
    }

///Order
function filter_DM(iddm){
    var isExisted = dmlist.filter(element => element == iddm);

    if(isExisted == ''){
        dmlist.push(iddm);
        console.log(dmlist);
        document.getElementById('DM-' + iddm).classList.add('active-select');
    }else{         
        dmlist.splice(dmlist.indexOf(isExisted) - 1, 1);
        console.log(dmlist);
        document.getElementById('DM-' + iddm).classList.remove('active-select');
    }
}

function filter_Hang(idhang){
    var isExisted = hanglist.filter(element => element == idhang);

    if(isExisted == ''){
        hanglist.push(idhang);
        console.log(hanglist);
        document.getElementById('Hang-' + idhang).classList.add('active-select');
    }else{         
        hanglist.splice(hanglist.indexOf(isExisted) - 1, 1);
        console.log(hanglist);
        document.getElementById('Hang-' + idhang).classList.remove('active-select');
    }
}

function filter_gia(idgia){
    var isExisted =  gialist.filter(element => element == idgia);

    if(isExisted == ''){
        if(gialist.length != 0){
            gialist.forEach(item => {
                console.log("item: " + item)
                document.getElementById(item).classList.remove('active-select')
            })
            gialist.splice(0, gialist.length)
        }
        gialist.push(idgia);
        console.log(gialist);
        document.getElementById(idgia).classList.add('active-select');
    }else{         
        gialist.splice(gialist.indexOf(isExisted) - 1, 1);
        console.log(gialist);
        document.getElementById(idgia).classList.remove('active-select');
    }

    switch(gialist[0]){
        case 'duoi2': giamin.value = formatPrice(0); giamax.value = formatPrice(2000000);break
        case '2to10': giamin.value = formatPrice(2000000); giamax.value = formatPrice(10000000);break
        case '10to20': giamin.value = formatPrice(10000000); giamax.value = formatPrice(20000000);break
        case 'tren20': giamin.value = formatPrice(20000000); giamax.value = formatPrice(100000000);break
        default: giamin.value = formatPrice(0); giamax.value = formatPrice(0);break
    }

    console.log(giamin.value)
}

function filter_All(){
    //Test code
    if(hanglist.length > 0){
        str += " AND ";
        var count = 0;
        hanglist.forEach(element => {
            str += count>0? (" OR sp.HANG=" + element ) : (" (sp.HANG=" + element);
            str += count==(hanglist.length-1)? ") " : "";
            count+=1;
        })
    }

    if(dmlist.length > 0) {
        str += " AND ";
        var count = 0;
        dmlist.forEach(element => {       
            str += count>0? (" OR sp.idDM=" + element ) : (" (sp.idDM=" + element);
            str += count==(dmlist.length-1)? ") " : "";
            count+=1;
        })
    }

    if(gialist.length > 0){
        const min = giamin.value
        let minmin = min.toString().replace(/\./g, '')
        const max = giamax.value
        let maxmax = max.toString().replace(/\./g, '')
        // console.log("min: " + minmin, " max: " + maxmax)
        str += ` AND ((sp.GIA*(1-sp.DISCOUNT/100)) BETWEEN ${minmin} AND ${maxmax})`
    }
    console.log("str: " + str);
    //Displaydata
    DisplayFilter();
    //Tắt filter
    closeFilter() ;
    //Test code
}

function ClearData(){
    //Reset data
    hanglist.forEach(element => {
        document.getElementById('Hang-'+ element).classList.remove('active-select');
    })
    hanglist.splice(0, hanglist.length);

    dmlist.forEach(element => {
        document.getElementById('DM-'+ element).classList.remove('active-select');
    })
    dmlist.splice(0, dmlist.length);

    gialist.forEach(element => {
        document.getElementById(element).classList.remove('active-select');
    })
    gialist.splice(0, gialist.length);
    
    str = str.replace(str, '');
    giamin.value = 0
    giamax.value = 0
}

// Phân trang nè
async function GetTotal() {
    const url = '../../Controller/productdetail/GetTotal.php'
    // order_str = (order_str !=''? order_str : ' GROUP BY sp.idSP ') 

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify ({
                sql: str,
                sql_order: order_str
            })
        })
        const total = response.json()
        console.log("total" + total)
        return total

    } catch(err) {
        console.error(err)
    }
}
// Phân trang nè
async function DisplayPage() {
    let totalProduct = await GetTotal();
    console.log('totalproduct: ' + totalProduct)
    productsPerPage = 15; // Số sản phẩm trên mỗi trang
    // productsPerPage;
    let totalPage = totalProduct / productsPerPage;

    if (totalPage > parseInt(totalPage)) {
        totalPage += 1;
        endpage = parseInt(totalPage);
    }

    endpage = parseInt(totalPage)
    console.log("endpage sau ấy: " + endpage)

    for (var i = 1; i <= totalPage; i+=1) {
        if(i==1) {
            pagination.innerHTML += `<li onclick='LoadPagePre()'><i class="fa fa-angle-left"></i></li>`
        }

        pagination.innerHTML += `<li onclick='LoadPage(${i})' class='${i === currentPage ? "active-page" : ""}'>${i}</li>`

        if (i== parseInt(totalPage)) {
            pagination.innerHTML += `<li onclick='LoadPageAfter()'><i class="fa fa-angle-right"></i></li>`
        }
    }
}

function LoadPage(page) {
    productgrid.innerHTML = "";
    pagination.innerHTML = '';
    currentPage = page;
    console.log("current page: " + currentPage)
    DisplayFilter()
}

function LoadPagePre() {
    productgrid.innerHTML = "";
    pagination.innerHTML = '';

    if(currentPage == 1) currentPage = 2

    currentPage -= 1
    console.log(currentPage)
    DisplayFilter()
}

function LoadPageAfter() {
    productgrid.innerHTML = "";
    pagination.innerHTML = '';

    console.log("endpage " + endpage)
    if(currentPage == parseInt(endpage)) currentPage = parseInt(endpage) - 1

    currentPage += 1
    console.log(currentPage)
    DisplayFilter()
}

async function FetchFilter() {
    const url = '../../Controller/productdetail/Filter.php'
    offset = (currentPage - 1) * productsPerPage;
    order_str = (order_str !=''? order_str : ' GROUP BY sp.idSP ') 

    try{
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                sql: str,
                sql_order: order_str,
                limit: productsPerPage,
                offset: offset
            })
        })

        const data = await response.json();
        return data;

        }catch(err) {
            console.error(err)
    }
}

async function DisplayFilter(){
    console.log("okokok")
    var displayproduct = document.getElementById('product-grid');
    const data = await FetchFilter();

    document.getElementById('product-grid').innerHTML = '';
    pagination.innerHTML = '';

    if(data.length == 0) {
        displayproduct.innerHTML = `<div id='notfoundSP'>Không tìm thấy sản phẩm</div>`;
    }

    data.forEach(element =>{
        var giaban = parseInt(element.gia)*(1 - parseFloat(parseInt(element.discount))/100);
        var star_str = '';     

        const content = `
        <div class="product-item">
            <a href="productdetail.php?id=${element.idsp}">
                <div class="product-img">
                    <img src="../../images/products/${element.img}" alt="${element.tensp}">
                    <div class="installment">
                        <img src="../../images/system/tragop.jpg" alt="">
                    </div>

                    <div class="discount-percentage" style="visibility: ${parseInt(element.discount)>0? 'visible' : 'hidden'}">
                        <p class="percent">
                            -<p class="sales-percent">${element.discount}</p>%
                        </p> 
                    </div>
                </div>
                <div class="name">${element.tensp}</div>
            </a>
            
            <div class="product-price">
                <div class="rating-index">
                    <p>
                        <i class="fa fa-heart-o likeIcon" id="likeIcon"></i> Yêu thích
                    </p>
                        <div class="stars" id='star-${element.idsp}'></div>
                </div>                   
                <div class="product-price-sales">
                    <div class="price">${formatPrice(giaban)}</div>
                    <div class="price-sales">${formatPrice(parseInt(element.gia))}đ</div>
                </div>                                           
            </div>`;

        for(let i = 0; i < 5; i++ ){
            if(i < parseInt(element.rate)){
                star_str += `<span class="star filled">
                                <i class="fa fa-star"></i>
                            </span>`
            }else{
                star_str += `<span class="star" style="display: ${parseInt(element.rate) == 0? 'none' : 'inline-block'}">
                                <i class="fa fa-star"></i>
                            </span>`
            }             
        }
        displayproduct.innerHTML += content;
        document.getElementById('star-'+ element.idsp).innerHTML= star_str; 
       
    })
    console.log("hang " + hanglist)
    console.log('dm' + dmlist)
    console.log("offset" + offset)
    console.log("currentpage" + currentPage)
    console.log('endpage ' + endpage)

    DisplayPage();
}
//Filter nè
//Bật tắt filter
function OpenFilter(button) {
    ClearData();
    document.getElementById("form-btn-filter").style.display = "block";
    window.addEventListener("wheel", preventScroll, { passive: false });
    window.addEventListener("touchmove", preventScroll, { passive: false });
}

function closeFilter() {
    // filter_All();
    document.getElementById("form-btn-filter").style.display = "none";
    window.removeEventListener("wheel", preventScroll);
    window.removeEventListener("touchmove", preventScroll);
}

// Đóng modal khi click ra ngoài
window.onclick = function(event) {
    let modal = document.getElementById("form-btn-filter");
    if (event.target === modal) {
        modal.style.display = "none";
        window.removeEventListener("wheel", preventScroll);
        window.removeEventListener("touchmove", preventScroll);
    }   
};

function preventScroll(event) {
    event.preventDefault(); // Chặn hành động cuộn
}
///////////////////////////////////////////////////////////////

//Yêu thích nè
document.addEventListener("DOMContentLoaded", function () {
    var favoriteIcons = document.querySelectorAll(".likeIcon");

    favoriteIcons.forEach(function (icon) {
        icon.addEventListener("click", function (event) {
            event.preventDefault(); // Ngăn chặn việc click vào link cha (nếu có)

            // Kiểm tra xem người dùng đã đăng nhập chưa
            var isLoggedIn = document.getElementById('userDefine').value;
            
            if (!isLoggedIn) {
                window.location.href = "../../View/login-register/dangnhap.php"; // Chuyển hướng đến trang đăng nhập
                return;
            }

            // Chuyển đổi trạng thái của biểu tượng yêu thích
            if (this.classList.contains('fa-heart-o')) {
                this.classList.remove('fa-heart-o'); 
                this.classList.add('fa-heart');     
            } else {
                this.classList.remove('fa-heart');  
                this.classList.add('fa-heart-o');   
            }
        });
    });
});
//Yêu thích nè

document.addEventListener("DOMContentLoaded", function() { 
    const openMergePrice = document.querySelector('.merge-price');
    const priceMerge = document.querySelector('.filter-merge-price');

    openMergePrice.addEventListener('click', function() {
        priceMerge.classList.toggle('active');
        openMergePrice.classList.toggle('active', priceMerge.classList.contains('active'));
        document.querySelectorAll('.chevron-down').forEach(function(chevron) {
            chevron.classList.toggle('rotate-up', priceMerge.classList.contains('active'));
            chevron.classList.toggle('rotate-down', !priceMerge.classList.contains('active'));
        });
    });
});

// Lọc sản phẩm theo hãng
// document.addEventListener("DOMContentLoaded", function() {
//     const brandFilters = document.querySelectorAll('.brands-option');
//     const deleteChoiceBrands = document.querySelector('.delete-choice-brands');
//     let activeBrandIds = new URLSearchParams(window.location.search).get('brands')?.split(',') || [];

//     if (activeBrandIds.length > 0) {
//         activeBrandIds.forEach(id => {
//             document.querySelector(`.brands-option[data-brand='${id}']`).classList.add('active');
//         });
//         if (activeBrandIds.length >= 2) {
//             deleteChoiceBrands.style.display = 'block';
//         }
//     }

//     brandFilters.forEach(function(brand) {
//         brand.addEventListener('click', function() {
//             const brandId = this.getAttribute('data-brand');

//             if (activeBrandIds.includes(brandId)) {
//                 activeBrandIds = activeBrandIds.filter(id => id !== brandId);
//                 this.classList.remove('active');
//             } else {
//                 activeBrandIds.push(brandId);
//                 this.classList.add('active');
//             }

//             if (activeBrandIds.length >= 2) {
//                 deleteChoiceBrands.style.display = 'block';
//             } else {
//                 deleteChoiceBrands.style.display = 'none';
//             }

//             fetchFilteredProducts(activeBrandIds);
//         });
//     });

    // deleteChoiceBrands.addEventListener('click', function() {
    //     activeBrandIds = [];
    //     brandFilters.forEach(b => b.classList.remove('active'));
    //     deleteChoiceBrands.style.display = 'none';
    //     fetchFilteredProducts(null);
    // });

    // function fetchFilteredProducts(brandIds) {
    //     const url = brandIds && brandIds.length > 0 ? `../../View/showproduct/productlist.php?brands=${brandIds.join(',')}` : `../../View/showproduct/productlist.php`;
    //     fetch(url)
    //         .then(response => response.text())
    //         .then(html => {
    //             const parser = new DOMParser();
    //             const doc = parser.parseFromString(html, 'text/html');
    //             const newProductGrid = doc.querySelector('.product-grid');
    //             const newPagination = doc.querySelector('.pagination');
    //             document.querySelector('.product-grid').innerHTML = newProductGrid.innerHTML;
    //             document.querySelector('.pagination').innerHTML = newPagination.innerHTML;
    //             updatePaginationLinks(brandIds);
    //         })
    //         .catch(error => {
    //             console.error('Không tìm thấy sản phẩm:', error);
    //         });
    // }

    // function updatePaginationLinks(brandIds) {
    //     const paginationLinks = document.querySelectorAll('.pagination a');
    //     paginationLinks.forEach(link => {
    //         const url = new URL(link.href);
    //         if (brandIds && brandIds.length > 0) {
    //             url.searchParams.set('brands', brandIds.join(','));
    //         } else {
    //             url.searchParams.delete('brands');
    //         }
    //         link.href = url.toString();
    //     });
    // }
// });