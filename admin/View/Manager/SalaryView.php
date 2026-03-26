<!-- CSS chung -->
<link rel="stylesheet" href="../../css/admin/OneForAll.css">
<link rel="stylesheet" href="../../css/admin/Salary.css">

<body onload="DisplayEmployees()">
    <div class="header">    
        <div class="first-header">
            <p>Tính lương</p>
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
            <h1 id='warning' style="display: none"></h1>
            <div class="filter">
                <strong>Tháng: </strong>
                <select id="month">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>

                <strong>Năm: </strong>
                <select id="year">
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                </select>

                <strong>Nhân viên: </strong>
                <select id="idNVSelect"></select>
                
                <button onclick="getCalendar()"><i class="fa-solid fa-search"></i></button>
            </div>

            <div class="content-detail">
                <h3>Thông tin nhân viên</h3>
                <div class="content-employee">
                    <div class="employeeInfo" id='employeeInfo'>
                        <div class="con" id='con'>
                            <div class="con-img">
                                <img id='imgNV' src="">
                            </div>
                            <div class="info">
                                <div class="info-item">
                                    <strong>ID Nhân viên:</strong>
                                    <p id='idNV'></p>
                                </div>

                                <div class="info-item">
                                    <strong>Họ tên:</strong>
                                    <p id='nameNV'></p>
                                </div>

                                <div class="info-item">
                                    <strong>Chức vụ:</strong>
                                    <p id='chucvuNV'></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="main-content" id='main-detail' style="display: none">
                        <div class="first-main-content">
                            <div id="calendar" class="wrapper calender"></div>

                            <div class="page-off" id='lich-container'>
                                <div class="page-off-content" id='leaveRequestList'>
                                    <h3>Đơn xin nghỉ phép</h3>
                                    <div id='List-rows'></div>
                                </div>
                            </div> 
                        </div>

                        <div class="second-main-content">
                            <div class='salary-container'>
                                <input type="hidden" id='idLUONG'>
                                <div class="salary-item">
                                    <strong>Lương cơ bản:</strong>
                                    <div class="salary-item-one">
                                        <input type="number" name='luongCB' readonly>(VND/giờ)
                                    </div>
                                </div>

                                <div class="salary-item">
                                    <strong>Tổng giờ làm thường (1.0):</strong>
                                    <input type="number" name='giolam' readonly>
                                </div>

                                <div class="salary-item">
                                    <strong>Tổng giờ tăng ca (1.5):</strong>
                                    <input type="number" name='tangca' readonly>
                                </div>
                                
                                <div class="salary-item">
                                    <strong>Tổng giờ làm lễ (2.0):</strong>
                                    <input type="number" name='giolamle' readonly>
                                </div>

                                <div class="salary-item">
                                    <strong>Số ngày làm trong tháng:</strong>
                                    <input type="number" name='days' readonly value="26">
                                </div>

                                <div class="salary-item">
                                    <strong>Số ngày vắng:</strong>
                                    <input type="number" name='absent' min='0' max='26' readonly>
                                </div>

                                <div class="salary-item">
                                    <strong>Số ngày làm thực tế (ngày thường):</strong>
                                    <input type="number" name='normal-work' readonly>
                                </div>

                                <div class="salary-item">
                                    <strong>Số ngày làm thực tế (ngày lễ):</strong>
                                    <input type="number" name='festive-work' readonly>
                                </div>
                                <div class="salary-item">
                                    <strong>Phụ cấp mặc định:</strong>
                                    <input type="number" name='default-allow'  value="2163000" readonly>
                                </div>

                                <div class="salary-item">
                                    <strong>Khấu trừ mặc định:</strong>
                                    <input type="number" name='default-deduct' readonly>
                                </div>

                                <div class="salary-item">
                                    <strong>Số ngày vắng có phép:</strong>
                                    <input type="number" name='absent-permission' min='0' max='26'>
                                </div>

                                <div class="salary-btn">
                                    <select name="paid" id="paid">
                                        <option value="1">Đã thanh toán</option>
                                        <option value="0" selected>Chưa thanh toán</option>
                                    </select>
                                </div>
                            </div>

                            <div class="edit-salary" id='edit-salary'>
                                <span>Lưu ý: Không thêm các loại phụ cấp, khấu trừ đã được quy định trong nội quy 
                                <span onclick="openInNewTab('/others/Noi-quy-lao-dong.pdf')" class='link'>(*)</span></span><br>
                                <div class="edit-salary-item" id='allowances'>
                                    <h3>Điều chỉnh phụ cấp</h3>
                                    <div class="nope">
                                        <div class="detail-item" id='allow-rows'>
                                            <div class="item-input" id='allow1'>
                                                <input type="text" placeholder="Loại phụ cấp 1" name='allowTxt-1' value="" onchange="ChangeAllow('text', 1)">
                                                <input type="number" placeholder="Giá trị" name='allowNum-1' value='0'  onchange="ChangeAllow('num', 1)">
                                            </div>
                                            <div id='allow-next-rows'></div>
                                        </div>
                                    </div>
                                    <button id='add-allow' class="btn-add">Thêm</button>
                                </div>
                                <div class="edit-salary-item" id='deductions'>
                                    <h3>Điều chỉnh khấu trừ</h3>
                                    <div class="nope">
                                        <div class="detail-item" id='deduct-rows'>
                                            <div class="item-input" id='deduct1'>
                                                <input type="text" placeholder="Loại khấu trừ 1"  name='deductTxt-1' value="Tạm ứng" readonly>
                                                <input type="number" placeholder="Giá trị" name='deductNum-1' value='0' onchange="ChangeDeduct('num', 1)">
                                            </div>
                                            <div id='deduct-next-rows'></div>
                                        </div>
                                    </div>
                                    <button id='add-deduct' class="btn-add">Thêm</button>
                                </div>
                            </div>
                        </div>

                        <div class="third-main-content">
                            <div class='salary-result'>
                                <div class="result-item">
                                    <strong>Lương chính:</strong>
                                    <input type="number" name='main-salary' readonly>
                                </div>

                                <div class="result-item">
                                    <strong>Tổng phụ cấp:</strong>
                                    <input type="number" name='total-allow' readonly >
                                </div>

                                <div class="result-item">
                                    <strong>Tổng khấu trừ:</strong>
                                    <input type="number" name='total-deduct' readonly>
                                </div>

                                <div class="result-item">
                                    <strong>Lương chính thức:</strong>
                                    <input type="number" name='true-total' readonly>
                                </div>
                            </div>
                        </div>

                        <div class="btn-end">
                            <a href="?page=employee"><button>Trở lại</button></a>
                            <button id='submit-btn' class="submit-btn" onclick="UpdatePaySlip()">Xác nhận</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

<script src="../../js/admin/Salary.js"></script>