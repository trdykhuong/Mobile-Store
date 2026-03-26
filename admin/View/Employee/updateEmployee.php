<?php
    include("../Controller/employee/updateEmployee.php");

    // $conn = mysqli_connect("localhost:3306", "root", "", "chdidong");

    // if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET['idTK'])) {
            $idTK = $_GET['idTK'];

            // Lấy thông tin nhân viên từ database
            $sql = "SELECT * FROM taikhoan tk join quyen q ON tk.idQUYEN=q.idQUYEN join nhanvien nv ON nv.idTK=tk.idTK WHERE nv.idTK = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $idTK);
            $stmt->execute();
            $result = $stmt->get_result();
            $employee = $result->fetch_assoc();

            // if (!$employee) {
            //     echo "Không tìm thấy nhân viên!";
            //     exit;
            // }
        // } else {
        //     echo "Không tìm thấy ID nhân viên!";
        //     exit;
        // }
    // }
}
?> 

<!-- CSS chung -->
<link rel="stylesheet" href="../../css/admin/OneForAll.css">
<link rel="stylesheet" href="../../css/admin/Employee_update.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<body>
    <div class="header">    
        <div class="first-header">
            <p>Cập nhật thông tin nhân viên</p>
        </div>
        <div class="second-header">
            <div class="second-header-main">
                <button class="home">
                    <a href="?page=employeeinfo"> 
                        <i class="fa-solid fa-house home-outline"></i>
                    </a>
                </button>
                <div class="line"></div>
            </div>
        </div>
    </div>

    <!-- Nội dung chính -->
    <main class="main">
        <div class='container'>
            <div class="head-update">
                <a href="?page=employee"><i class="fa-solid fa-arrow-left"></i></a>
                <h3>Thông tin nhân viên</h3>
            </div>

            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="idTK" value="<?= htmlspecialchars($idTK) ?>">
                <input type="hidden" name="img-name" value="<?= $employee['IMG'] ?>">
                <input type="hidden" name="vtrilviec" value="<?= $employee['idQUYEN'] ?>">

                <div class="form-content">
                    <div class="img-update">
                        <img id="img" src="../../images/employee/<?= $employee['IMG'] ?>" alt="">
                        <button type="button" onclick="document.getElementById('change-img').click()">Chọn ảnh</button>
                        <input type="file" id="change-img" name="Img" accept="image/*" onchange="hienThiAnh(event)">
                    </div>

                    <div class="info-update">
                        <div class="info-update-content">
                            <div class="info-update-content-item">
                                <label>Họ tên:</label>
                                <input type="text" name="hoten" value="<?= htmlspecialchars($employee['HOTEN']) ?>">
                            </div>
                            <span class="error" id="hotenError"></span>
                        </div>

                        <div class="info-update-content">
                            <div class="info-update-content-item">
                                <label>Ngày sinh:</label>
                                <input type="date" name="date" value="<?= htmlspecialchars($employee['NGAYSINH']) ?>">
                            </div>
                            <span class="error" id="dateError"></span>
                        </div>

                        <div class="info-update-content">
                            <div class="info-update-content-item">
                                <label>Giới tính:</label>
                                <div class="detail-item">
                                    <div class="radio-item">
                                        <input type="radio" name="gender" value="1" <?= ($employee['GIOITINH'] == 1) ? 'checked' : '' ?>> Nam
                                    </div>
                                    <div class="radio-item">
                                        <input type="radio" name="gender" value="0" <?= ($employee['GIOITINH'] == 0) ? 'checked' : '' ?>> Nữ
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="info-update-content">
                            <div class="info-update-content-item">
                                <label>Địa chỉ:</label>
                                <input type="text" name="address" value="<?= htmlspecialchars($employee['DIACHI']) ?>">
                            </div>
                            <span class="error" id="addressError"></span>
                        </div>
                    </div>

                    <div class="line-page"></div>

                    <div class="info-update">
                        <div class="info-update-content">
                            <div class="info-update-content-item">
                                <label>Số điện thoại:</label>
                                <input type="text" name="phone" value="<?= htmlspecialchars($employee['SDT']) ?>">
                            </div>
                            <span class="error" id="phoneError"></span>
                        </div>

                        <div class="info-update-content">
                            <div class="info-update-content-item">
                                <label>Email:</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($employee['EMAIL']) ?>">
                            </div>
                            <span class="error" id="emailError"></span>
                        </div>

                        <div class="info-update-content">
                            <div class="info-update-content-item">
                                <label>Ngày vào làm:</label>
                                <input type="date" name="ngaylam" value="<?= htmlspecialchars($employee['NGAYVAOLAM']) ?>" readonly>
                            </div>
                            <span class="error" id="ngaylamError"></span>
                        </div>
                        <div class="info-update-content">
                            <div class="info-update-content-item">
                                <label>Vị trí làm việc:</label>
                                <?php 
                                    $mode= $_GET['mode'];
                                    if($mode == 'update'){
                                        echo '<select name="QUYEN" id="quyenSelect" onchange="toggleNgayNhanChuc()">';
                                        $result = mysqli_query($conn, "SELECT idQUYEN, TENQUYEN FROM quyen WHERE idQUYEN <> 1 AND idQUYEN <> 0");
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $selected = ($employee['idQUYEN'] == $row['idQUYEN']) ? "selected" : "";
                                            if($_SESSION["role"] == 4) {
                                                continue;
                                            }
                                            echo "<option value='{$row['idQUYEN']}' $selected>{$row['TENQUYEN']}</option>";
                                        }
                                        echo '</select>';
                            
                                    }else{
                                        echo '
                                        <input type="hidden" value="'.$employee['idQUYEN']. '" id="quyenSelect">
                                        <input type="text" value="'.$employee['TENQUYEN'].'" readonly>';
                                    }
                              
                                ?>                            
                            </div>
                           
                        </div>
                        <div class="info-update-content">
                            <div class="info-update-content-item" id="ngayNhanChucDiv" style="display: none;">
                                <label>Ngày nhận chức:</label>
                                <input type="date" name="ngay_nhan_chuc">                        
                            </div>
                            <span class="error" id="ngayncError"></span>
                        </div>
                    </div>
                </div>

                <div class="btn-update-end">
                    <input type="submit" value="Cập nhật thông tin" name="updateEmployee">
                </div>
            </form>
            <!-- <button type="button" id="return">X</button> -->
        </div>
    </main>
</body>

<script>
    function hienThiAnh(event) {
        var reader = new FileReader();
        reader.onload = function () {
            document.getElementById("img").src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function toggleNgayNhanChuc() {
        let vitrilv = document.querySelector("select[name='QUYEN']").value;
        let currentRole = document.querySelector('input[name=vtrilviec]').value; // Chuyển thành chuỗi

        console.log("Chức vụ hiện tại:", currentRole);
        console.log("Chức vụ đã chọn:", vitrilv);

        let ngayNhanChucDiv = document.getElementById("ngayNhanChucDiv");

        if (currentRole !== vitrilv) {
            ngayNhanChucDiv.style.display = "block";
        } else {
            ngayNhanChucDiv.style.display = "none";
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector("form").addEventListener("submit", function (event) {
            var img = document.querySelector('input[name="txtImg"]');
            let hoten = document.querySelector("[name='hoten']").value.trim();
            let ngaySinhInput = document.querySelector("[name='date']").value;
            let gioitinh = document.querySelector("[name='gender']:checked").value;
            let diachi = document.querySelector("[name='address']").value;
            let phone = document.querySelector("[name='phone']").value.trim();
            let email = document.querySelector("[name='email']").value.trim();
            let idQUYEN = document.querySelector("select[name='QUYEN']").value;
            let ngayNhanChucDiv = document.getElementById("ngayNhanChucDiv");

            console.log("quyen " + idQUYEN);

            // Chặn form submit mặc định
            let isValid = true;

            // Xóa thông báo lỗi cũ
            document.querySelectorAll(".error").forEach(e => e.textContent = "");

            if (hoten === "") {
                document.getElementById("hotenError").textContent = "Họ tên không được để trống.";
                isValid = false;
            }

            if (diachi === "") {
                document.getElementById("addressError").textContent = "Địa chỉ không được để trống.";
                isValid = false;
            }

            let today = new Date();
            // Đặt giờ, phút, giây về 0 để tránh lỗi so sánh
            today.setHours(0, 0, 0, 0);
            if (ngaySinhInput === "") {
                document.getElementById("dateError").textContent = "Vui lòng nhập ngày sinh.";
                isValid = false;
            } else {
                var ngaySinh = new Date(ngaySinhInput);
                ngaySinh.setHours(0, 0, 0, 0);

                if (ngaySinh >= today) {
                    document.getElementById("dateError").textContent = "Ngày sinh phải nhỏ hơn ngày hiện tại.";
                    isValid = false;
                } else {
                    let age = today.getFullYear() - ngaySinh.getFullYear();
                    let monthDiff = today.getMonth() - ngaySinh.getMonth();
                    let dayDiff = today.getDate() - ngaySinh.getDate();

                    // Kiểm tra nếu sinh nhật chưa đến trong năm nay thì giảm tuổi đi 1
                    if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
                        age--;
                    }

                    if (age < 18) {
                        document.getElementById("dateError").textContent = "Nhân viên phải đủ 18 tuổi.";
                        isValid = false;
                    }
                }
            }

            let phoneRegex = /^0[0-9]{9}$/;
            if (!phoneRegex.test(phone)) {
                document.getElementById("phoneError").textContent = "Số điện thoại phải có 10 chữ số.";
                isValid = false;
            }

            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                document.getElementById("emailError").textContent = "Email không hợp lệ.";
                isValid = false;
            }

            if (ngayNhanChucDiv <= today) {
                document.getElementById("ngaylamError").textContent = "Ngày nhận chức không được bé hơn ngày hiện tại.";
                isValid = false;
            }

            // Nếu có lỗi thì dừng lại
            if (!isValid) {
                event.preventDefault();
                console.log("Form validation failed!");
                return;
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = urlParams.get("page");

        if (currentPage === "updateEmployee") {
            const detailOrderEffect = document.querySelector(".update-employee-effect");
            if (detailOrderEffect) {
                detailOrderEffect.classList.add("active"); 
            }
        }
    });
</script>