document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const currentPage = urlParams.get("page");

    if (currentPage === "SalaryView") {
        const detailOrderEffect = document.querySelector(".update-employee-effect");
        if (detailOrderEffect) {
            detailOrderEffect.classList.add("active"); 
        }
    }
});

const month = document.getElementById('month')
const year = document.getElementById('year')
const idNVP = document.getElementById('idNV')
const warning = document.getElementById('warning')

//Edit cái ấy phụ cấp
let allowList = new Array()
let allowIndex = 1
let allow1 = new Array(allowIndex, '', 0)
allowList.push(allow1)

//Edit cái ấy khấu trừ
let deductList = new Array()
let deductIndex = 1
///////////////////

function openInNewTab(url) {
    window.open(url, '_blank').focus();
}

// setTimeout(
// ZoomDate = (idDate, nghi, isLe, ngayle, tenngayle)=>{
//     const dateText = document.getElementById('date-' + idDate)
// }, 3000)

function ClearData(){
    allowList.splice(1, allowList.length - 1)
    deductList.splice(0, deductList.length)

    document.getElementById('allow-next-rows').innerHTML = ''
    document.getElementById('deduct-next-rows').innerHTML = ''

    allowIndex = 1
    deductIndex = 1

    document.querySelector('select[name=paid]').value = 0
    document.querySelector('input[name=allowTxt-1').value = ''
    document.querySelector('input[name=allowNum-1').value = 0
    document.querySelector('input[name=deductNum-1').value = 0
}

LoadRequestList = async(idNV, monthval, yearval) =>{
    url = '../../admin/Controller/Employee/LoadRequestList.php';
    
    const RequestList = async () =>{
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idNV: idNV,
                    month: monthval,
                    year: yearval
                })
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
                <table>
                    <tr>
                        <th>Ngày gửi</th>
                        <th>Ngày nghỉ</th>
                        <th>Lý do</th>
                    </tr>
                    <tr>
                        <td>${element.ngaygui}</td>
                        <td>${element.ngaynghi}</td>
                        <td>${element.lydo}</td>
                    </tr>
                </table>
            </div>`
    })
}

async function DisplayEmployees(){
    const url = '../../admin/Controller/Employee/EmpoyeeList.php' 

    const GetIdList = async()=>{
        try {
            const response = await fetch(url,{
                method: 'GET',
                headers: {
                'Content-Type': 'application/json'
                }
            })

            const data = response.json()
            return data
        } catch (error) {
            console.error(error)
        }
    }

    const idList = await GetIdList()
    const selectId = document.getElementById('idNVSelect')

    selectId.innerHTML = ''
    idList.forEach(element=>{
        selectId.innerHTML += `<option value='${element.id}'>${element.id}__${element.name}</option>`
    })

    getCalendar()
}

daysInMonth = (month, year) => {
    return new Date(year, month, 0).getDate();
}

const getInfo = async (idNV)=>{
    url = '../../admin/Controller/Employee/EmployeeInfoController.php'
    try {
        const response = await fetch(url,{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: idNV
            })
        })

        const data = response.json()
        return data
    } catch (error) {
        console.error(error)
    }
}

getCalendar = async ()=>{
    url = '../../admin/Controller/Manager/SalaryCalculate/GetCalendar.php'
    //Lấy id
    const idNV = document.getElementById('idNVSelect').value
    ///
    const monthval = parseInt(month.value)
    const yearval = parseInt(year.value)

    const Calendar = async()=>{
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    idNV: idNV,
                    month: monthval,
                    year: yearval
                 })
            })

            const data = response.json()
            return data
        } catch (error) {
            console.error(error)
        }
    }

    const calendar = await Calendar()
    console.log(calendar)
    let lamle = 0
    const days = daysInMonth(month.value, year.value)
    const box = document.getElementById('calendar')

    //Thông tin nhân viên
    const info = await getInfo(idNV)
    document.getElementById('idNV').innerText = info.id
    document.getElementById('nameNV').innerHTML = info.hoten
    document.getElementById('chucvuNV').innerHTML = info.quyen
    document.getElementById('imgNV').src = '../../images/employee/' + info.img
    document.querySelector('input[name=luongCB]').value = parseFloat(info.luong)

    //Giờ làm
    let tong_gio_lam = 0
    let tong_lam_le = 0
    calendar.forEach(element =>{
        if(element.le == null){
            if(element.totalHour[0].total == null) element.totalHour[0].total = 0
            console.log(parseInt(element.totalHour[0].total))
            tong_gio_lam += parseInt(element.totalHour[0].total)
            
        }else{
            if(element.totalHour[0].total == null) element.totalHour[0].total = 0
            console.log(parseInt(element.totalHour[0].total))
            tong_lam_le += parseInt(element.totalHour[0].total)
        }
    })

    document.querySelector('input[name=giolam]').value = parseInt(tong_gio_lam)
    document.querySelector('input[name=giolamle]').value = parseInt(tong_lam_le)

    //Giờ tăng ca (làm y rang)
    let tong_tang_ca = 0
    calendar.forEach(element =>{
        if (element.totalHour[1] == null){
            // if(element.totalHour[1].total == null) element.totalHour[1].total = 0
            return
        }
        if(element.totalHour[1].total == null) element.totalHour[1].total = 0
        // console.log()
        tong_tang_ca += parseInt(element.totalHour[1].total)
    })

    console.log(tong_tang_ca)
    document.querySelector('input[name=tangca]').value = tong_tang_ca
    //////////

    //Reset cái lịch trc khi chèn
    box.innerHTML = ''
    for(var index = 1; index <= days; index++) {
        let absent = false
        var le = false
        var ngayle = ''
        var tenngayle = ''

        calendar.forEach(element => {
            if(index == element.ngay) absent = true
            if(index == element.ngay && element.le != null) {
                le = true
                ngayle = element.ngayle
                tenngayle = element.tenngayle
                lamle += 1
            }
        });
        box.innerHTML += `<div class='day-box' id='date-${index}' onclick="ZoomDate(${index},${!absent}, ${le}, '${ngayle}', '${tenngayle}')" style='background-color: ${ !absent? 'white' : (le? 'red' :  '#0ddc14')};
        color:${absent? 'white' : 'black'}'>${index}<br>${absent?'<i class="fa-solid fa-check"></i>':''}</div>`
    }
    //Chèn cái ngày dô
    document.querySelector('input[name=normal-work]').value = calendar.length - lamle
    document.querySelector('input[name=festive-work]').value = lamle
    const absent = 26 - calendar.length
    document.querySelector('input[name=absent]').value = absent

    ClearData()
    await LoadRequestList(idNV, monthval, yearval)
    await LoadPaySlipDetail()
    SalaryCaculator()
}

LoadPaySlipDetail = async() =>{
    const url = '../../admin/Controller/Manager/SalaryCalculate/PayslipInfo.php'

    const getDetail = async() =>{
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    idNV: parseInt(idNVP.innerText),
                    month: parseInt(month.value),
                    year: parseInt(year.value)
                })
            })

            const data = response.json()
            return data
        } catch (error) {
            console.error(error)
        }
    }

    const payslip = await getDetail()
    if(payslip.status == false){
        warning.innerHTML = payslip.message
        warning.style.display = 'block'
        document.getElementById('main-detail').style.display = 'none'
        return
    }

    document.getElementById('idLUONG').value = payslip.idluong
    document.getElementById('main-detail').style.display = 'block'
    warning.style.display = 'none'
    if(payslip.luong > 0){
        document.querySelector('input[name=luongCB]').value = payslip.luong
    }
    if(payslip.chucvu != ''){
        document.getElementById('chucvuNV').value = payslip.chucvu
    }
    if(payslip.ghichuPC != '' && payslip.ghichuPC != null){
        const phucap = (payslip.ghichuPC).split('\n')
        // allowList.shift()
        await phucap.forEach(pc => {
            const pctemp = pc.split(',')
            allowList.push(pctemp)
        })

        DisplayAllowDeduct('allow', allowList)
    }

    if(payslip.ghichuKT != '' && payslip.ghichuKT != null){
        const khautru = (payslip.ghichuKT).split('\n')
        // deductList.shift()
        await khautru.forEach(kt => {
            const kttemp = kt.split(',')
            deductList.push(kttemp)
        })
        DisplayAllowDeduct('deduct', deductList)
    }
    document.querySelector('input[name=deductNum-1]').value = payslip.tamung
}

const allowContainer = document.getElementById('allow-next-rows')
SpawnAllow = (idAllow, txt, num) =>{
    allowContainer.innerHTML +=
    `<div id='allow${idAllow}' class="item-input">
        <input type="text" placeholder="Loại phụ cấp ${idAllow}" name='allowTxt-${idAllow}' value="${txt}" onchange="ChangeAllow('text', ${idAllow})">
        <input type="number" placeholder="Giá trị" name='allowNum-${idAllow}' value="${parseFloat(num)}" onchange="ChangeAllow('num', ${idAllow})">
        <button onclick=DeleteAllowance(${idAllow})>X</button>
    </div>`
}

const deductContainer = document.getElementById('deduct-next-rows')
SpawnDeduct = (idDeduct, txt, num) =>{
    deductContainer.innerHTML +=
    `<div id='deduct${idDeduct}' class="item-input">
        <input type="text" placeholder="Loại khấu trừ ${idDeduct}" name='deductTxt-${idDeduct}' value="${txt}" onchange="ChangeDeduct('text', ${idDeduct})">
        <input type="number" placeholder="Giá trị" name='deductNum-${idDeduct}' value="${parseFloat(num)}" onchange="ChangeDeduct('num', ${idDeduct})">
        <button onclick=DeleteDeduction(${idDeduct})>X</button>
    </div>`
}

DisplayAllowDeduct = (type, array) =>{
    switch(type){
        case 'allow':{
            // allowIndex = array.length
            array.forEach(element =>{   
                // if(element[0] == 0 || element[0] == undefined) return
                if(element[0] == 1){
                    document.querySelector('input[name=allowTxt-1]').value = element[1]
                    document.querySelector('input[name=allowNum-1]').value = parseFloat(element[2])
                    return
                }
                allowIndex += 1
                SpawnAllow(allowIndex, element[1], element[2])
            })
        } break

        case 'deduct':{
            // deductIndex = array.length
            array.forEach(element =>{       
                if(element[0] == 0 || element[0] == undefined || element[0] == 1) return
                deductIndex += 1
                SpawnDeduct(deductIndex, element[1], element[2])
            })
        } break
    }
    
}
///Hàm tính toán ấy ấy đồ cho phụ cấp
document.getElementById('add-allow').addEventListener('click', ()=>{
    allowIndex += 1
    const newAllow = new Array(allowIndex, '', 0)
    allowList.push(newAllow)
    
    SpawnAllow(allowIndex, '', 0)
})

DeleteAllowance = (idAllow)=>{
    const isExisted = allowList.filter(allow => allow[0] == idAllow)
    ///Xó allow đó
    allowList.splice(allowList.indexOf(isExisted[0]), 1)
    allowIndex -= 1

    //Load lại danh sách allow
    allowContainer.innerHTML = ''
    allowList.forEach(allow =>{
        if(allow[0] == 1) return
        SpawnAllow(allow[0], allow[1], allow[2])
    })

    SalaryCaculator()
}

ChangeAllow = (type, idAllow) =>{
    let currentAllow

    switch(type){
        case 'text': {
            currentAllow = document.querySelector('input[name=allowTxt-' + idAllow + ']')
            allowList[idAllow - 1][1] = currentAllow.value
        }break;
        case 'num': {
            currentAllow = document.querySelector('input[name=allowNum-' + idAllow + ']')
            allowList[idAllow - 1][2] = currentAllow.value
        }break;
        default: break;
    }

    SalaryCaculator()
}

///Hàm tính toán ấy ấy đồ cho khấu trừ
document.getElementById('add-deduct').addEventListener('click', ()=>{
    deductIndex += 1
    const newDeduct = new Array(deductIndex, '', 0)
    deductList.push(newDeduct)

    SpawnDeduct(deductIndex, '', 0)
})

DeleteDeduction = (idDeduct)=>{
    const isExisted = deductList.filter(deduct => deduct[0] == idDeduct)
    ///Xó allow đó
    deductList.splice(deductList.indexOf(isExisted[0]), 1)
    deductIndex -= 1

    //Load lại danh sách allow
    deductContainer.innerHTML = ''

    deductList.forEach(deduct =>{
        if(deduct[0] == 1) return
        SpawnDeduct(deduct[0], deduct[1], deduct[2])
    })

    SalaryCaculator()
}

ChangeDeduct = (type, idDeduct) =>{
    let currentDeduct
    if(idDeduct != 1){
        switch(type){
            case 'text': {
                currentDeduct = document.querySelector('input[name=deductTxt-' + idDeduct + ']')
                deductList[idDeduct - 2][1] = currentDeduct.value
            }break;
            case 'num': {
                currentDeduct = document.querySelector('input[name=deductNum-' + idDeduct + ']')
                deductList[idDeduct - 2][2] = currentDeduct.value
            }break;
            default: break;
    }  }

    SalaryCaculator()
}

//Hàm tính tổng cộng (Lương cơ bản x (số giờ * hệ số + tăng ca * hệ số) + tổng phụ cấp - tổng khấu trừ)

async function SalaryCaculator(){

    ///Tổng phụ cấp
    let allowSum = 0
    allowList.forEach(allow =>{
        if(allow[2] == '') allow[2] = 0
        allowSum += parseFloat(allow[2])
    })

    allowSum += parseFloat(document.querySelector('input[name=default-allow]').value)
    document.querySelector('input[name=total-allow]').value = parseFloat(allowSum).toFixed(2)

    ///Cái cái l lư lươ lươn lương
    const luongCB = document.querySelector('input[name=luongCB]').value
    const tongGiolam = document.querySelector('input[name=giolam]').value
    const tongGiolamLe = document.querySelector('input[name=giolamle]').value
    const tongTangCa = document.querySelector('input[name=tangca]').value
    //////////
    //Lương chính
    let luongchinh = luongCB * (parseFloat(tongGiolam) + parseFloat(tongTangCa) * 1.5 + parseFloat(tongGiolamLe) * 2)
    document.querySelector('input[name=main-salary]').value = parseFloat(luongchinh).toFixed(2)

    let Luong = luongchinh  + parseFloat(allowSum) 

    //Tính khấu trừ mặc định
    let default_deduct = parseFloat(8 / 100 + 1.5 / 100 + 1 / 100 + 15 / 100) * Luong - 0.75
    document.querySelector('input[name=default-deduct]').value = default_deduct

    //Tổng khấu trừ
    const tamung = document.querySelector('input[name=deductNum-1]').value
    let deductSum = 0
    deductList.forEach(deduct =>{
        if(deduct[2] == '') deduct[2] = 0
        deductSum += parseFloat(deduct[2])
    })

    deductSum += parseFloat(document.querySelector('input[name=default-deduct]').value) 
    deductSum += parseFloat(tamung)
    document.querySelector('input[name=total-deduct]').value = parseFloat(deductSum).toFixed(2)

    //Tổng lương
    let totalLuong = Luong - parseFloat(deductSum)
    document.querySelector('input[name=true-total]').value = totalLuong.toFixed(2)
}

UpdatePaySlip = async() =>{
    const url = '../../admin/Controller/Manager/SalaryCalculate/UpdatePaySlip.php'
    ///Chuẩn bị thông tin bảng lương
    const total = parseFloat(document.querySelector('input[name=true-total]').value).toFixed(2)
    //Tính phụ cấp
    const default_allow = parseFloat(document.querySelector('input[name=default-allow]').value)
    const phucap =  (parseFloat(document.querySelector('input[name=total-allow]').value) - default_allow).toFixed(2)
    //Tạm ứng sẽ được lưu riêng
    const tamung = parseFloat(document.querySelector('input[name=deductNum-1]').value).toFixed(2)
    //Tính khấu trừ
    const default_deduct = parseFloat(document.querySelector('input[name=default-deduct]').value).toFixed(2)
    const khautru = (parseFloat(document.querySelector('input[name=total-deduct]').value) - default_deduct - tamung).toFixed(2)
    //Tình trạng
    const tinhtrang = document.querySelector('select[name=paid]').value
    //Xử lý danh sách phụ cấp để biết chỗ đó gồm những mục nào
    const ghichuPC = allowList.join('\n')
    // const deductTemp = deductList.shift()
    const ghichuKT = deductList.length > 0? (deductList.length > 1? deductList.join('\n') : deductList.join(',')) : ''

    //Lấy lương cơ bản tại thời điểm tạo phiếu
    const currentLuong = parseFloat(document.querySelector('input[name=luongCB]').value)
    //Lấy chức vụ hiện tại
    const currentChucvu = document.getElementById('chucvuNV').innerText
    //Lương chính
    const luongchinh = document.querySelector('input[name=main-salary]').value

    const sendData = async () =>{
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idNV: parseInt(idNVP.innerText),
                    month: parseInt(month.value),
                    year: parseInt(year.value),
                    phucap: phucap,
                    khautru: khautru,
                    tongtien: total,
                    ghichuPC: ghichuPC,
                    ghichuKT: ghichuKT,
                    tamung: tamung,
                    tinhtrang: tinhtrang,
                    luongcb: currentLuong,
                    chucvu: currentChucvu,
                    luongchinh: luongchinh
                })
            })
    
            const data = response.json()
            return data
        } catch (error) {
            console.error(error)
        }
    }

    const message = await sendData()
    alert(message.message)
}

