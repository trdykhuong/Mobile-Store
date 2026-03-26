<?php
$conn = mysqli_connect("localhost", "root", "", "chdidong", 3306);
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

$sqll = "SELECT COUNT(*) AS songay FROM ngaynghi WHERE TRANGTHAI = 'Chưa duyệt'";
$result_nn = mysqli_query($conn, $sqll);
$row_nn = mysqli_fetch_assoc($result_nn);
$pendingRequests = $row_nn['songay'];

// $query = "SELECT tk.idTK, tk.HOTEN, tk.NGAYSINH, tk.GIOITINH, tk.SDT, tk.EMAIL, tk.DIACHI, tk.NGAYVAOLAM, tk.LUONGCB, tk.TRANGTHAI, tk.IMG, q.TENQUYEN
//           FROM taikhoan tk
//           JOIN quyen q ON tk.idQuyen = q.idQuyen";
// $result = mysqli_query($conn, $query);
// $employees = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>



<link rel="stylesheet" href="../../css/admin/OneForAll.css">
<link rel="stylesheet" href="../../css/admin/Employee_Page.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<body onload="ASRequest()">
    <div class="header">    
        <div class="first-header">
            <p>Quản lý nhân viên</p>
        </div>
        <div class="second-header">
            <div class="second-header-main">
                <button class="home">
                    <a href="?page=employeeinfo"> 
                        <i class="fa-solid fa-house home-outline"></i>
                    </a>
                </button>
                <div class="line"></div>
                <a href="?page=add_employee" class="add-btn"><button>Thêm nhân viên</button></a>
                <!-- <a href='?page=TakeLeave'><button>Nghỉ lễ</button></a>
                <a href='?page=requestLeave'>
                    <button>
                        Nghỉ phép
                        <?php if ($pendingRequests > 0): ?>
                            <span class="mess-off">
                                *
                            </span>
                        <?php endif; ?>
                    </button>
                </a> -->

                <!-- <a href="#">
                    <button>Lịch tăng ca</button>
                </a> -->
                <!-- <button onclick="OpenAS()">Ứng lương
                    <span id="AS-quantity" style="display: none">0</span>
                </button>
                <a href='?page=SalaryView'><button>Tính lương</button></a> -->
                <!-- <button onclick="OpenSalaryCal('../View/Manager/SalaryView.php')">Tính lương</button> -->
            </div>
        </div>
    </div>
          
     <!-- Nội dung chính --> <!-- Này của t(CẢNH) -->
    <main class="main">
        <div class="container">
            <?php include("../View/employee/employeeFilter.php"); ?>

            <div class="content-employee">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th width="15%">Họ Tên</th>
                            <th>Ngày vào làm</th>
                            <th width="5%">Giới tính</th>
                            <th>Số điện thoại</th>
                            <th>Email</th>
                            <th>Địa chỉ</th>
                            <!-- <th>Ngày sinh</th> -->
                            <!-- <th>Lương cơ bản</th> -->
                            <th>Vị trí</th>
                            <th class='action_butt'>Hành động</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $employees = mysqli_fetch_all($result, MYSQLI_ASSOC); ?>
                        <?php if (count($employees) > 0): ?>
                            <?php foreach ($employees as $row): $path = "../../images/employee/" . $row['IMG']; ?>
                                <tr class="row-table <?= $row['TRANGTHAI'] == 0 ? 'locked' : '' ?>">
                                    <td><?= $row['idTK'] ?></td>
                                    <td><?= htmlspecialchars($row['HOTEN']) ?></td>
                                    <td><?= htmlspecialchars($row['NGAYVAOLAM']) ?></td>
                                    <td><?= htmlspecialchars($row['GIOITINH']) == 0 ? "Nữ" : "Nam" ?></td>
                                    <td><?= htmlspecialchars($row['SDT']) ?></td>
                                    <td><?= htmlspecialchars($row['EMAIL']) ?></td>
                                    <td><?= htmlspecialchars($row['DIACHI']) ?></td>
                                    <!-- <td><?= htmlspecialchars($row['NGAYSINH']) ?></td> -->
                                    <!-- <td><?= htmlspecialchars($row['LUONGCB']) ?></td> -->
                                    <td><?= $row['TENQUYEN'] ?></td>
                                    <td class='btn-action'>
                                        <div class="btn-action-detail">
                                            <?php if ($row['TRANGTHAI'] == 1): ?>
                                                <!-- <form action='../View/employee/updateEmployee.php' method='POST'> -->
                                                <a href='?page=updateEmployee&idTK=<?= $row['idTK'] ?>&mode=update' class='btn-update'>
                                                    <!-- <input type='hidden' name='idTK' value='<?= $row['idTK'] ?>'> -->
                                                    <!-- <button type='submit' class='btn-update' name="update">Cập nhật</button> -->
                                                    Cập nhật
                                                </a>
                                                <!-- </form> -->
                                            <?php endif; ?>

                                            <button type='button' class='btn-toggle-status <?= $row['TRANGTHAI'] == 1 ? 'locked' : 'unlocked' ?>' data-id='<?= $row['idTK'] ?>'>
                                                <i class="fa-solid <?= $row['TRANGTHAI'] == 1 ? 'fa-lock' : 'fa-unlock' ?>"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" style="text-align:center;">Hiện tại cửa hàng chưa có nhân viên nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="modal" id="modal">
                <div id='AS-requests' class="AS-requests">
                    <h1>Danh sách ứng lương</h1>
                    <div id='ASList'></div>
                    <div class="btn-clUL">
                        <button class="closeULForm" onclick="CloseULForm()">Trở lại</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>


<script>

OpenAS = () =>{
    document.getElementById("modal").classList.add("open-modal");
    document.getElementById("AS-requests").classList.add("AS-requests-open");
    document.querySelector(".hidden-log-out").classList.add("active");
    
}

CloseULForm = () => {
    document.getElementById("modal").classList.remove("open-modal");
    document.getElementById("AS-requests").classList.remove("AS-requests-open");
    document.querySelector(".hidden-log-out").classList.remove("active");
}
async function ASRequest(){
    const url = '../../admin/Controller/Manager/AdvanceSalary/ASRequestList.php'
    const ASquantity = document.getElementById('AS-quantity')
    const ASList = document.getElementById('ASList')

    const getRequest = async()=>{
        try {
            const response = await fetch(url, {
                method: "GET",
                headers: { 'Content-Type': 'application/json' }
            })

            const data = response.json()
            return data
        } catch (error) {
            console.error(error)
        }
    }

    const requestList = await getRequest()
    if(requestList.length <= 0){
        ASquantity.style.display = 'none'
        return
    }

    ASquantity.innerHTML = requestList.length
    ASquantity.style.display = 'block'

    ASList.innerHTML = ''
    requestList.forEach(rq => {
    ASList.innerHTML += `<div class="ASRows">
                    <div>${rq.hoten}</div>
                    <div>${rq.tamung}</div>
                    <div>${rq.lydo}</div>
                    <select onchange="HandleAS(${rq.idluong}, ${rq.tamung}, this.value)">
                        <option value="Chưa duyệt" selected>Chưa duyệt</option>
                        <option value="Duyệt">Duyệt</option>
                        <option value="Bị từ chối">Bị duyệt</option>
                    </select>
                </div>
                `
    })
    //Fill cái list lên
    console.log(requestList)
}

HandleAS = async(idluong, tamung, trangthaimoi) =>{
    console.log(trangthaimoi)
    if(trangthaimoi == 'Chưa duyệt') return

    url = '../../admin/Controller/Manager/AdvanceSalary/HandleAS.php'

    const handle = async () =>{
        try {
            const response = await fetch(url, {
                method: "POST",
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    idluong: idluong,
                    tamung: tamung,
                    trangthaimoi: trangthaimoi
                })
            })

            const data = response.json()
            return data
        } catch (error) {
            console.error(error)
        }
    }

    const result = await handle()
    alert(result.message)
    ASRequest()
}
/////

OpenSalaryCal=(url)=>{
    window.open(url, '_blank').focus();
}
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".btn-toggle-status").forEach(button => {
        button.addEventListener("click", function () {
            let idTK = this.getAttribute("data-id");
            let row = this.closest("tr");
            let currentStatus = this.querySelector("i").classList.contains("fa-lock") ? "Khóa" : "Mở khóa";
            let updateButton = row.querySelector(".btn-update");

            // Gửi yêu cầu AJAX để cập nhật trạng thái
            fetch("../Controller/employee/toggleStatus.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `idTK=${idTK}`
            })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "success") {
                        const icon = this.querySelector("i");
                        if (currentStatus === "Khóa") {
                            icon.classList.remove("fa-lock");
                            icon.classList.add("fa-unlock");
                            this.classList.remove("locked");
                            this.classList.add("unlocked");
                            row.classList.add("locked"); 
                            if (updateButton) updateButton.style.display = "none"; 
                        } else {
                            icon.classList.remove("fa-unlock");
                            icon.classList.add("fa-lock");
                            this.classList.remove("unlocked");
                            this.classList.add("locked");
                            row.classList.remove("locked"); 
                            if (updateButton) updateButton.style.display = "inline-block"; 
                            location.reload();  
                        }
                    } else {
                        alert("Lỗi: " + data);
                    }
                })
                .catch(error => console.error("Lỗi:", error));
        });
    });
});
</script>
    