
<?php 
    include('../../Controller/connectDB.php');
    $id = isset($_GET['idLUONG'])? $_GET['idLUONG'] : 0;

    $hesothue = 0.255;
    $phucapmacdinh = 2163000;
    $sql = "SELECT * from bangluong b JOIN nhanvien n ON b.idNV=n.idTK JOIN taikhoan t ON b.idNV=t.idTK
    WHERE b.idLUONG=$id";
    $get_payslip = mysqli_query($conn, $sql);
    $slips = mysqli_fetch_assoc($get_payslip);

?>
<link rel="stylesheet" href="../../../css/admin/Payslip.css">
<div class="main">
    <div class="h2">Phiếu lương</div>
    <div class="row1">Công ty TNHH MTV HKT-CM</div>
    <div class="row1">Địa chỉ: 273 An Dương Vương, phường 5, quận 3, TP. HCM</div>
    <div class="row1" id='date-title'>Sài Gòn, tháng  <?= $slips['THANG'] ?>, năm  <?= $slips['NAM'] ?></div>

    <div class="row">
        <div class='col1'></div>
        <div class="text col2">Mã nhân viên</div>
        <div class='output-text col3'><?= $slips['idNV'] ?></div>
        <div class='col4'></div>
        <div class="text col5">Ngày công đi làm</div>
        <div class='output-text col6'>26</div>
    </div>    
    <div  class="row">
        <div class='col1'></div>
        <div class="text col2">Họ tên nhân viên</div>
        <div class='output-text col3'><?= $slips['HOTEN'] ?></div>
        <div class='col4'></div>
        <div class="text col5">Ngày công thực tế</div>
        <div class='output-text col6' id='chamcongtt'></div>
    </div>
    <div  class="row">
        <div class='stt col1'></div>
        <div class="text col2">Chức vụ</div>
        <div class='output-text col3'><?= $slips['CHUCVUHIENTAI'] ?></div>
        <div class='col4'></div>
        <div class="text col5">Lương cơ bản</div>
        <div class='output-text col6'><?= $slips['LUONGHIENTAI'] ?></div>
    </div>
    <!-- <div class="row">
        <div class='col1'></div>
        <div class="text col2">Làm ngày lễ</div>
        <div class='output-text col3' id='ngayle' ></div>
        <div class='col4'></div>
        <div class="text col5">Hệ số ngày lễ</div>
        <div class='output-text col6'>2.0</div>
    </div> -->
    <div class="row">
        <div class='stt col1'></div>
        <div class='text col2'></div>
        <div class='output-text col3'></div>
        <div class='stt col4'></div>
        <div class='text col5'></div>
        <div class='output-text col6'></div>
    </div>
    <div class="row">
        <div class="stt col1">STT</div>
        <div class='text col2'> Các khoản thu nhập</div>
        <div class='stt col3'> (VND) </div>
        <div class="stt col4">STT</div>
        <div class='text col5'> Các khoản trừ vào lương</div>
        <div class='stt col6'> (VND) </div>
    </div> 
    <div class="row">
        <div class='stt col1'>1</div>
        <div class='text col2'>Lương chính</div>
        <div class='output-text col3'><?= $slips['LUONGCHINH'] ?></div>
        <div class='stt col4'>1</div>
        <div class='text col5'>Bảo hiểm bắt buộc</div>
        <div class='output-text col6'></div>
    </div>
    <div class="row">
        <div class='stt col1'>2</div>
        <div class='text col2'>Phụ cấp</div>
        <div class='output-text col3'></div>
        <div class='stt col4'>1.1</div>
        <div class='text col5'>Bảo hiểm xã hội (8%)</div>
        <div class='output-text col6' ><?= ($slips['LUONGCHINH'] + $phucapmacdinh) * 8 / 100 ?></div>
    </div>
    <div class="row">
        <div class='stt col1'>2.1</div>
        <div class='text col2'>Trách nhiệm</div>
        <div class='output-text col3' id='divachnhiem'>243000</div>
        <div class='stt col4'>1.2</div>
        <div class='text col5'>Bảo hiểm y tế (1.5%)</div>
        <div class='output-text col6'><?= ($slips['LUONGCHINH'] + $phucapmacdinh) * 1.5 / 100 ?></div>
    </div>
    <div class="row">
        <div class='stt col1'>2.2</div>
        <div class='text col2'>Ăn trưa</div>
        <div class='output-text col3' id='antrua'>780000</div>
        <div class='stt col4'>1.3</div>
        <div class='text col5'>Bảo hiểm thất nghiệp (1%)</div>
        <div class='output-text col6'><?= ($slips['LUONGCHINH'] + $phucapmacdinh) / 100?></div>
    </div>
    <div class="row">
        <div class='stt col1'>2.3</div>
        <div class='text col2'>Điện thoại</div>
        <div class='output-text col3' id='dienthoai'>100000</div>
        <div class='stt col4'>2</div>
        <div class='text col5'>Thuế thu nhập cá nhân</div>
        <div class='output-text col6' id='thue'><?= (($slips['LUONGCHINH'] + $phucapmacdinh) * 15 / 100) - 0.75?></div>
    </div>
    <div class="row">
        <div class='stt col1'>2.4</div>
        <div class='text col2'>Xăng xe</div>
        <div class='output-text col3'>240000</div>
        <div class='stt col4'>3</div>
        <div class='text col5'>Tạm ứng</div>
        <div class='output-text col6'><?= $slips['TAMUNG'] ?></div>
    </div>
    <div class="row">
        <div class='stt col1'>2.5</div>
        <div class='text col2'>Nhà ở</div>
        <div class='output-text col3'>800000</div>
        <div class='stt col4'>4</div>
        <div class='text col5'>Khác</div>
        <div class='output-text col6' id='khac'><?= $slips['KHAUTRU'] ?></div>
    </div>
    <div class="row">
        <div class='stt col1'>2.6</div>
        <div class='text col2'>Khác</div>
        <div class='output-text col3'><?= $slips['PHUCAP'] ?></div>
        <div class='stt col4'></div>
        <div class='text col5'></div>
        <div class='output-text col6'></div>
    </div>
    <div class="row">
        <div class="col1"></div>
        <div class='text col2'>Tổng cộng</div>
        <div class='output-text col3'><?= $slips['PHUCAP'] + $phucapmacdinh + $slips['LUONGCHINH']?></div>
        <div class="col4"></div>
        <div class='text col5'>Tổng cộng</div>
        <div class='output-text col6'><?= $slips['KHAUTRU'] + $hesothue * ($slips['LUONGCHINH'] + $phucapmacdinh) - 0.75 + $slips['TAMUNG']  ?></div>
    </div>
    <div class="row">
        <div class="col1"></div>
        <div class="col2"></div>
        <div class="col3"></div>
        <div class="col4"></div>
        <div class="col5"></div>
        <div class="col6"></div>
    </div>
    <div class="row">
        <div class="col1"></div>
        <div class='text col2'>Tổng thành tiền</div>
        <div class='output-text col3' id='tongcong'><?= ( $slips['PHUCAP'] + $slips['LUONGCHINH'] + $phucapmacdinh) - ($slips['KHAUTRU'] + $hesothue * ($slips['LUONGCHINH'] + $phucapmacdinh) - 0.75 + $slips['TAMUNG']  )?></div>
        <div class="col4"></div>
        <div class="col5"></div>
        <div class="col6"></div>
    </div>
    <div class="row">
        <div class="col1"></div>
        <div class='text col2'></div>
        <div class='output-text col3'></div>
        <div class="col4"></div>
        <div class="col5"></div>
        <div class="col6"></div>
    </div>
</div>
