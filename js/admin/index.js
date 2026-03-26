// ---- Ẩn hiện sidebar ---- //
let sidebar = document.querySelector(".sidebar");
let closeBtn = document.querySelector("#btn-menu");

closeBtn.addEventListener("click", () => {
    sidebar.classList.toggle("open");
    // menuBtnChange();
});

document.addEventListener("DOMContentLoaded", function() {
    // Lấy giá trị của `page` từ URL
    const urlParams = new URLSearchParams(window.location.search);
    const currentPage = urlParams.get("page") || "employeeinfo"; // Mặc định là employeeinfo

    // Tìm tất cả các liên kết trong sidebar
    const navLinks = document.querySelectorAll(".nav-list a");

    // Thêm lớp `active` vào liên kết tương ứng
    navLinks.forEach(link => {
        if (link.getAttribute("data-page") === currentPage) {
            link.classList.add("active");
        } else {
            link.classList.remove("active");
        }
    });
});

function LoadUserInfo() {
    const api = '../../admin/Controller/getUserInfo.php';

    fetch(api, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Lỗi:', data.error);
            } else {
                document.getElementById('img-avt').src = "../../images/employee/" + data.img;
                document.getElementById('name-menu').innerHTML = data.hoten;
                document.getElementById('position-menu').innerHTML = "Chức vụ: " + data.quyen;
            }
        })
        .catch(error => console.error('Lỗi:', error));
}

document.addEventListener("DOMContentLoaded", LoadUserInfo());