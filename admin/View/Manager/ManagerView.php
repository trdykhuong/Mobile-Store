<link rel="stylesheet" href="../../../css/admin/Manager.css">

<button id="btn-tb" onclick="openPopup('sub-menu', 'sub-menu-popup')"><p id="phep-quantity">0</p></button>
<div class="sub-menu" id="sub-menu">
    <h2>Đơns xin nghỉ phép</h2>
    <ul class='listPhep' id='listPhep'></ul>
</div>

<div class="detail" id="detail">
    <h2>Thông tin phép</h2>
    <input type="hidden" name='idnv' value=''>
    <img name='imgnv' src="" alt="">
    <p name='tennv'>Tên nhân viên: </p>
    <p name='ngaynghi'>Ngày xin nghỉ: </p>
    <p name='lydo'>Lý do: </p>
    <button name="duyet" onclick="DuyetPhep('duyet')">Duyệt</button>
    <button name='tuchoi' onclick="DuyetPhep('tuchoi')">Từ chối</button>
    <button name='huy' onclick="closePopup('detail', 'detail-popup')">X</button>
</div>

<script src="../../../js/admin/Manager.js"></script>