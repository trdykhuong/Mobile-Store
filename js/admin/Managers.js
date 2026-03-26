const modal = document.getElementById("modal");
const formDetailAsset = document.getElementById("detail-asset");
const formLeave = document.getElementById("leaveForm");

document.addEventListener('click', (e)=> {
    if (modal.contains(e.target) 
        && !formDetailAsset.contains(e.target) 
        && !formLeave.contains(e.target)) {
            modal.classList.remove("openeditModal");
            closeLeaveForm();
    }
})

document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const currentPage = urlParams.get("page");

    if (currentPage === "requestLeave") {
        const detailOrderEffect = document.querySelector(".update-employee-effect");
        if (detailOrderEffect) {
            detailOrderEffect.classList.add("active"); 
        }
    }
});

function openLeaveForm() {
    modal.classList.add("open-modal");
    formLeave.classList.add("open-popup-form");
    document.querySelector(".hidden-log-out").classList.add("active");
}

function closeLeaveForm() {
    modal.classList.remove("open-modal");
    formLeave.classList.remove("open-popup-form");
    formLeave.classList.remove("open-detail-asset"); // Xóa cả class này nếu cần
    document.querySelector(".hidden-log-out").classList.remove("active");
}

function validateLeaveForm() {
    // Lấy thông tin ngày nghỉ, lý do và ID nhân viên
    const ngaynghi = document.getElementById('ngaynghi').value;
    const lydo = document.getElementById('lydo').value;
    const idNV = document.getElementById('nhanvien').value;

    // Kiểm tra các trường có được điền đầy đủ không
    if (!ngaynghi || !lydo || !idNV) {
        alert("Vui lòng điền đầy đủ thông tin.");
        event.preventDefault();
        return false;
    }

    // Kiểm tra xem ngày nghỉ có lớn hơn ngày hôm nay không
    const today = new Date();  // Lấy ngày hiện tại
    const leaveDate = new Date(ngaynghi);  // Chuyển ngày nghỉ thành đối tượng Date

    // So sánh ngày nghỉ với ngày hiện tại
    if (leaveDate <= today) {
        alert("Ngày nghỉ phải lớn hơn ngày hôm nay.");
        event.preventDefault();
        return false;
    }

    return true;  // Nếu tất cả các điều kiện đúng, cho phép gửi form
}

function DuyetNghiPhep() {
    // Lấy giá trị từ các trường trong form
    const ngaynghi = document.getElementById('ngaynghi').value;
    const lydo = document.getElementById('lydo').value;
    const idNV = document.getElementById('nhanvien').value;
    console.log(idNV)

    // Kiểm tra các trường có được điền đầy đủ không
    if (!ngaynghi || !lydo || !idNV) {
        alert("Vui lòng điền đầy đủ thông tin.");
        event.preventDefault();
        return ;
    }

    // Kiểm tra xem ngày nghỉ có lớn hơn ngày hôm nay không
    const today = new Date();  // Lấy ngày hiện tại
    const leaveDate = new Date(ngaynghi);  // Chuyển ngày nghỉ thành đối tượng Date

    // So sánh ngày nghỉ với ngày hiện tại
    if (leaveDate <= today) {
        alert("Ngày nghỉ phải lớn hơn ngày hôm nay.");
        event.preventDefault();
        return ;
    }


    // Tiến hành gửi yêu cầu lên server thông qua AJAX (fetch)
    fetch('../Controller/Manager/SubmitLeaveRequest.php', {
        method: 'POST',
        headers: {
           'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            idNV: idNV,
            ngaynghi: ngaynghi,
            lydo: lydo
        })
    })
    .then(response => response.json())
    .then(data => {

        if (data.status === 'success') {
            alert('Yêu cầu nghỉ phép đã được gửi thành công!');
            closeLeaveForm(); // Đóng form sau khi gửi thành công
            window.location.reload();  // Tải lại trang
        } else {
            console.log("là ko dô đây à")
            alert('Có lỗi xảy ra: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Có lỗi xảy ra:', error);
        alert('Không thể gửi yêu cầu nghỉ phép.');
    });
}

DuyetPhep = async(option)=>{
    const url = "../Controller/Manager/HandleNghiPhep.php"
    var id = document.querySelector("input[name=idnv]").value
    const idnv = id.slice(2, id.length) 
    const ngaynghi_str = document.querySelector('span[name=ngaynghi]').innerHTML
    const ngaynghi = ngaynghi_str.slice(0, ngaynghi_str.length)
    console.log(idnv)
    
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
    window.location.href = window.location.href;
}
    
function openFormUpdate(button) {
    const idTK = button.getAttribute('data-idtk');
    const imgFileName = button.getAttribute('data-img'); 

    document.getElementById('popup-idTK').value = idTK;

    const row = button.closest('tr');
    const cells = row.querySelectorAll('td');

    document.getElementById('popup-idTK').value = idTK;
    document.querySelector("input[name='idnv']").value = cells[0].innerText; 
    document.querySelector("span[name='tennv']").innerText = cells[1].innerText; 
    document.querySelector("span[name='ngaynghi']").innerText = cells[3].innerText; 
    document.querySelector("span[name='lydo']").innerText =cells[4].innerText; 

    document.getElementById('imgnv').src = "../../images/employee/" + imgFileName;
    console.log("Image file name:", imgFileName);

    modal.classList.add("open-modal");
    formDetailAsset.classList.add("open-detail-asset");
    document.querySelector(".hidden-log-out").classList.add("active");
}

function filterTable() {
    let searchValue = document.getElementById('search').value.toLowerCase();
    let selectedMonth = document.getElementById('filter-month').value; // yyyy-mm
    let rows = document.querySelectorAll('#leave-list tr');

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        let matchSearch = text.includes(searchValue);

        // Lấy ngày nghỉ từ cột thứ 4 (index 3)
        let ngayNghiText = row.cells[3]?.innerText.trim(); // ex: "2025-04-07"
        let matchMonth = true;

        if (selectedMonth) {
            let rowMonth = ngayNghiText.substring(0, 7); // "yyyy-mm"
            matchMonth = rowMonth === selectedMonth;
        }

        row.style.display = matchSearch && matchMonth ? '' : 'none';
    });
}

closePopup= (id, classpop) =>{
    // var container = document.getElementById(id);
    // container.classList.remove(classpop);
    modal.classList.remove("open-modal");
    formDetailAsset.classList.remove("open-detail-asset");
    document.querySelector(".hidden-log-out").classList.remove("active");
}