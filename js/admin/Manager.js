let listphep = document.getElementById("listPhep");
let phepQuan = document.getElementById('phep-quantity');
// LoadPhep()

// async function LoadPhep(){
// const url = "../../Controller/Manager/LoadNghiPhep.php"
// let fetchListNghi = async () => {
//     try{
//     var response = await fetch(url, {
//         method: 'GET',
//         headers: {
//         'Content-Type': 'application/json'
//         }
//     })
//     const data = response.json()
//     return data

//     }catch(err) {
//         console.error(err)
//     }} 

// listphep.innerHTML = ''
// const data = await fetchListNghi()
//     data.forEach(element => {
//     listphep.innerHTML += `<li onclick='LoadDetailNghi(${element.idnv}, "${element.ngaynghi}")'><img src="/images/employee/${element.img}" alt=""> <p>${element.tennv}</p> <p>${element.trangthai==0? "Chưa duyệt" : "Đã duyệt"}</p></li>`
//     })

// phepQuan.innerHTML = data.length
// }

LoadDetailNghi = async (idnv, ngaynghi)=>{
    const url = '../../Controller/Manager/LoadDetailPhep.php'
    const loadDetail = async()=>{
        const response = await fetch(url, {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                idNV: idnv,
                ngaynghi: ngaynghi
            })
        })

        const data = response.json()
        return data
    }

    const detailPhep = await loadDetail()

    //Clear trước
    document.querySelector("p[name=tennv]").innerHTML = ''
    document.querySelector('p[name=ngaynghi]').innerHTML = ''
    document.querySelector('p[name=lydo]').innerHTML = ''
    //CLear rồi
    document.querySelector("input[name=idnv]").value = detailPhep.idnv
    document.querySelector('img[name=imgnv]').src = "/images/employee/" + detailPhep.imgnv
    document.querySelector("p[name=tennv]").innerHTML = "Tên nhân viên: " + detailPhep.tennv
    document.querySelector('p[name=ngaynghi]').innerHTML = "Ngày xin nghỉ: " + detailPhep.ngaynghi
    document.querySelector('p[name=lydo]').innerHTML = "Lý do: " + detailPhep.lydo

    // closePopup('sub-menu', 'sub-menu-popup')
    openPopup("detail", "detail-popup")
}

document.addEventListener('click', (event) =>{
    // var container = document.getElementById("sub-menu")
    // var btn_tb = document.getElementById("btn-tb")
    var detailcontains = document.getElementById("detail")
    // var btn_huy = document.querySelector('button[name=huy]')
    var btn_duyet = document.querySelector('button[name=duyet]')
    var btn_tuchoi = document.querySelector('button[name=tuchoi]')

    // if(!container.contains(event.target)){
    //     // console.log("123")
    //     closePopup('sub-menu', 'sub-menu-popup')
    // }

    if(!detailcontains.contains(event.target) && !btn_duyet.contains(event.target) && !btn_tuchoi.contains(event.target)){
        closePopup('detail', 'detail-popup')
    }
})


document.addEventListener('click', (event) => {
    var detailcontains = document.getElementById("detail")
    var btn_duyet = document.querySelector('button[name=duyet]')
    var btn_tuchoi = document.querySelector('button[name=tuchoi]')

    if (
        detailcontains &&
        !detailcontains.contains(event.target) &&
        (!btn_duyet || !btn_duyet.contains(event.target)) &&
        (!btn_tuchoi || !btn_tuchoi.contains(event.target))
    ) {
        closePopup('detail', 'detail-popup')
    }
})

openPopup= (id, classpop) =>{
    var container = document.getElementById(id);
    container.classList.add(classpop)
}
closePopup= (id, classpop) =>{
    var container = document.getElementById(id);
    container.classList.remove(classpop)
}

DuyetPhep = async(option)=>{
    const url = "../../Controller/Manager/HandleNghiPhep.php"
    const idnv = document.querySelector("input[name=idnv]").value
    const ngaynghi_str = document.querySelector('p[name=ngaynghi]').innerHTML
    const ngaynghi = ngaynghi_str.slice(15, ngaynghi_str.length)

    const getMess = async() =>{
        try{
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    option: option,
                    idNV: idnv,
                    ngaynghi: ngaynghi
                })
            })

            const message = response.json()
            return message

        }catch(err){
            console.error(err)
        }}

        const reply = await getMess()
        alert(reply.message)
        closePopup('detail', 'detail-popup')
    // LoadPhep()
}

document.getElementById("leaveForm").addEventListener("submit", function(event) {
    // Gọi hàm kiểm tra khi người dùng gửi form
    if (!validateLeaveForm()) {
        // Nếu validate trả về false, ngừng gửi form
        event.preventDefault();
    } else {
        DuyetNghiPhep();
    }
});

function openLeaveForm() {
    document.getElementById("leave-form").style.display = "block";
}

function closeLeaveForm() {
    document.getElementById("leave-form").style.display = "none";
}
