// Gọi gì gì đó
const str = document.getElementById("idNV").value;
const modal = document.getElementById("modal");
const leavepopup = document.getElementById("leave-form");
const ungluong = document.getElementById('UngLuong')

let salarypopup = document.getElementById("salary-form");
let list_plieu_luong = document.getElementById('PL-List');
let formPageOff = document.getElementById('leaveRequestList');
let count = 0;

// Đơn vị tiền VND
function formatCurrencyVND(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount);
}

// click modal
document.addEventListener('click', (e)=> {
    if (modal.contains(e.target) 
        && !leavepopup.contains(e.target) 
        // && !salarypopup.contains(e.target) 
        && !list_plieu_luong.contains(e.target)
        && !formPageOff.contains(e.target)
        && !ungluong.contains(e.target)) {
            console.log(123)
            modal.classList.remove("open-modal");
            closeLeavePop()
            // closeSalaryPop()
            closePLList()
            CloseUL()
            closeLeaveRequestList()
    }
})

document.getElementById('year').value = GetYear().getFullYear()
//Get year
function GetYear(){
    const now = new Date()
    return now
}
//Load thông tin nhân viên
function LoadInfo(){
    try{
    const api = '../../admin/Controller/Employee/EmployeeInfoController.php';
    fetch(api, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: str
        })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('img-em').src = "../../images/employee/" + data.img;
            document.getElementById('name').querySelector('.value').innerHTML = data.hoten;
            document.getElementById('gioitinh').querySelector('.value').innerHTML = data.gioitinh == 1 ? 'Nam' : 'Nữ';
            var parts = data.ngaysinh.split('-');
            document.getElementById('ngaysinh').querySelector('.value').innerHTML = parts[2] + '/' + parts[1] + '/' + parts[0];
            document.getElementById('email').querySelector('.value').innerHTML = data.email;
            document.getElementById('sdt').querySelector('.value').innerHTML = data.sdt;
            document.getElementById('diachi').querySelector('.value').innerHTML = data.diachi;
            document.getElementById('tinhtrang').querySelector('.value').innerHTML = data.tinhtrang;
            
            //Xin nghỉ
            document.getElementById('name-leave').querySelector('.value').innerHTML = data.hoten;
            document.getElementById('idNV-leave').querySelector('.value').innerHTML = str;

            //Lương
            // document.getElementById('idNV-salary').querySelector('.value').innerHTML = str;
            // document.getElementById('name-salary').querySelector('.value').innerHTML = data.hoten;
            // document.getElementById('vitri').querySelector('.value').innerHTML = data.quyen;
            // document.getElementById('luong').querySelector('.value').innerHTML = formatCurrencyVND(data.luong) + " VNĐ/tháng";
        })
    }catch(error){
        console.error(error)
    }

    LoadRequestList()
}

//Danh sách đơn xin phép
LoadRequestList = async() =>{
    url = '../../admin/Controller/Employee/LoadRequestList.php';
    
    const RequestList = async () =>{
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ idNV: str})
            })

            const data = response.json()
            return data
        } catch (error) {
            console.error(error)
        }
    }

    const list = await RequestList()
    const displayList = document.getElementById('List-rows')

    displayList.innerHTML = ''
    list.forEach(element =>{
        displayList.innerHTML += `<div class='leave-item'><p>${element.ngaygui}</p>
                                <p>${element.ngaynghi}</p>
                                <p>${element.lydo}</p>
                                <p>${element.trangthai}</p></div>`
    })
    // console.log(list)
}

// Nghỉ phép
function OpenLeavePopup(){
    modal.classList.add("open-modal");
    leavepopup.classList.add("open-leaveform");
    document.querySelector(".hidden-log-out").classList.add("active");
}

function closeLeavePop(){
    modal.classList.remove("open-modal");
    leavepopup.classList.remove("open-leaveform");
    document.querySelector(".hidden-log-out").classList.remove("active");
}

//Gửi đơn nghỉ
function SendLeaveRequest(){
    const ngaynghi = document.getElementById('ngaynghi').value;
    const lydo = document.getElementById("lydo").value;
    const url = "../../admin/Controller/Employee/SendLeaveRequest.php";

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: str,
            ngaynghi : ngaynghi,
            lydo : lydo
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.status >= 0){
            alert(data.message);

            if(data.status < 4)  closeLeavePop();
        }
    })
    .catch(error => console.error('Error:', error))

    LoadRequestList()
}

//Danh sách đơn xin phép
function OpenLeaveRequestList(){
    modal.classList.add("open-modal");
    formPageOff.classList.add("open-leaveRequestList");
    document.querySelector(".hidden-log-out").classList.add("active");
}

function closeLeaveRequestList(){
    modal.classList.remove("open-modal");
    formPageOff.classList.remove("open-leaveRequestList");
    document.querySelector(".hidden-log-out").classList.remove("active");
}

LoadRequestList = async() => {
    url = '../../admin/Controller/Employee/LoadRequestList.php';
    const RequestList = async () =>{
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ idNV: str})
            })

            const data = response.json()
            return data
        } catch (error) {
            console.error(error)
        }
    }

    const list = await RequestList()
    const displayList = document.getElementById('List-rows')

    displayList.innerHTML = ''
    list.forEach(element =>{
        displayList.innerHTML += `
            <div class='leave-item'>
                <table class='leave-table'>
                    <thead>
                        <tr>
                            <th>Ngày gửi</th>
                            <th>Ngày nghỉ</th>
                            <th>Lý do</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>${element.ngaygui}</td>
                            <td>${element.ngaynghi}</td>
                            <td>${element.lydo}</td>
                            <td>${element.trangthai}</td>
                        </tr>
                    </tbody>
                </table>
            </div>`
    })
    // console.log(list)
}

// Xem lương
function OpenSalaryPopup(){
    modal.classList.add("open-modal");
    salarypopup.classList.add("open-salaryform");
    document.querySelector(".hidden-log-out").classList.add("active");
}

function closeSalaryPop(){
    modal.classList.remove("open-modal");
    salarypopup.classList.remove("open-salaryform");
    document.querySelector(".hidden-log-out").classList.remove("active");
}

//Xem phiếu lương
function closePLList(){ 
    modal.classList.remove("open-modal");
    list_plieu_luong.classList.remove("open-PL-list");
    document.querySelector(".hidden-log-out").classList.remove("active");
}

LoadListLuong = async() => {
    modal.classList.add("open-modal");
    list_plieu_luong.classList.add("open-PL-list");
    document.querySelector(".hidden-log-out").classList.add("active");
    const url = '../../admin/Controller/Manager/SalaryCalculate/LoadPayslipList.php?idNV=' + str

    const LoadPL = async() => {
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })

            const data = response.json()
            return data
        } catch(err) {
            console.error(err)
        }
    }

    const listPL = await LoadPL()
    list_plieu_luong.innerHTML = `
        <h1>Giai đoạn lương</h1>
        <div class='head-timing'>
            <p>Thời gian</p>
            <p>Tổng tiền (VND)</p>    
            <p>Tình trạng</p>    
        </div>
        <div class='PL-content'></div>
        <div class='PL-footer'>
            <button class='PL-close' onclick='closePLList()'>Xác nhận</button>
        </div>
    `;

    const plContent = document.querySelector('.PL-content');

    listPL.forEach(element => {
        plContent.innerHTML += `
        <div class='timing-hook' onclick='PrintPaySlip(${element.idPL})'>
            <input type='hidden' id='idPL' value='${element.idPL}'></input>
            <p>${element.month}/${element.year}</p>
            <p>${formatCurrencyVND(element.total)}</p>
            <p>${(element.status)==1? 'Đã nhận' : 'Chưa nhận'}</p>
        </div>`;
    });
}

//Khúc này ứng lương
OpenUL = ()=>{
    modal.classList.add("open-modal")
    ungluong.classList.add("open-UngLuong")
    document.querySelector(".hidden-log-out").classList.add("active")
}

CloseUL = ()=>{
    modal.classList.remove("open-modal")
    ungluong.classList.remove("open-UngLuong")
    document.querySelector(".hidden-log-out").classList.remove("active")
    ResetUL()
}
//Xác nhận ứng lương
SendUL = async()=>{
    const url = '../../admin/Controller/Employee/AdvanceSalary.php'
    const idNV = document.getElementById('idNV').value
    const month = document.getElementById('month').value
    const year = document.getElementById('year').value
    const lydo = document.getElementById('lydoung').value
    const tienung = document.getElementById('tienung').value

    //Check đầu vào
    if(parseInt(year) < 2024 || !Number.isInteger(parseInt(year))){
        alert('Năm khum hộp lệ')
        return
    }

    if(parseInt(tienung) <= 0 || !Number.isInteger(parseInt(tienung))){
        alert('Tiền ứng khum hộp lệ')
        return
    }

    if(lydo == ''){
        alert("Nhập lý do")
        return
    }

    const sending = async ()=> {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idNV: idNV,
                    month: month,
                    year: year,
                    lydo: lydo,
                    tienung: tienung
                })
            })

            const data = response.json()
            return data
        } catch (error) {
            console.error(error)
        }
    }

    const result = await sending()
    alert(result.message)
    if(result.status != false) ResetUL()

    console.log(result)
}

ResetUL = ()=>{
    document.getElementById('month').value = 1
    document.getElementById('year').value = GetYear().getFullYear()
    document.getElementById('lydoung').value = ''
    document.getElementById('tienung').value = 0
}

PrintPaySlip = (idPL) =>{
    const url = '../View/Employee/Payslip.php?idLUONG=' + idPL
    window.open(url, '_blank').focus();
}

//In phiếu lương
// document.addEventListener('DOMContentLoaded', function() {
//     // Lấy các phần tử
//     var loaderFrame = document.getElementById('loaderFrame');
//     var printerButton = document.getElementById('salary-printer');

//     loaderFrame.addEventListener('load', function() {
//         var iframeWindow = loaderFrame.contentWindow || loaderFrame.contentDocument.defaultView;
//         iframeWindow.print();
//     });

//     printerButton.addEventListener('click', function() {
//         loaderFrame.setAttribute('src', '../View/Employee/Payslip.php?idBL=1');
//     });
// });