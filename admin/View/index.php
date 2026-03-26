<?php
    include "checkLogin.php";

    $connect = new mysqli("localhost:3306", "root", "", "chdidong");

    if ($connect->connect_error) {
        die("Connection failed: " . $connect->connect_error);
    }
?>

<title>Admin Control Panel</title>
<link rel="stylesheet" href="../../css/admin/index.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<body>
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="../../images/system/logo copy.png" alt="" class="logo">
            <div class="logo-name">MyPow Store</div>
            <i class="fa-solid fa-angle-left" id="btn-menu"></i>
        </div>
        <div class="avt">
            <img id="img-avt" src="" alt="Ảnh đại diện">
            <p id="name-menu"></p>
            <p id="position-menu"></p>
        </div>
        <ul class="nav-list">
            <li>
                <?php if (checkPermission('Thông tin nhân viên')): ?>
                    <a href="?page=employeeinfo" data-page="employeeinfo">
                        <i class="fa-solid fa-user"></i>
                        <span class="links-name">Thông tin nhân viên</span>
                    </a>
                    <span class="tooltip">Thông tin nhân viên</span>
                <?php endif; ?>
            </li>
            <li class="update-employee-effect">
                <?php if (checkPermission('Nhân viên')): ?>
                    <a href="?page=employee" data-page="employee">
                        <i class="fa-solid fa-users"></i>
                        <span class="links-name">Nhân viên</span>
                    </a>
                    <span class="tooltip">Nhân viên</span>
                <?php endif; ?>
            </li>
            <!-- <li>
                <?php if (checkPermission('Lịch làm việc')): ?>
                    <a href="?page=calendar" data-page="calendar">
                        <i class="fa-solid fa-calendar-days"></i>
                        <span class="links-name">Lịch làm việc</span>
                    </a>
                    <span class="tooltip">Lịch làm việc</span>
                <?php endif; ?>
            </li> -->
            <li>
                <?php if (checkPermission('Tài khoản')): ?>
                    <a href="?page=account" data-page="account">
                        <i class="fa-solid fa-user-gear"></i>
                        <span class="links-name">Tài khoản</span>
                    </a>
                    <span class="tooltip">Tài khoản</span>
                <?php endif; ?>
            </li>
            <li>
                <?php if (checkPermission('Phân quyền')): ?>
                    <a href="?page=role" data-page="role">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span class="links-name">Phân quyền</span>
                    </a>
                    <span class="tooltip">Phân quyền</span>
                <?php endif; ?>
            </li>
            <li>
                <?php if (checkPermission('Sản phẩm')): ?>
                    <a href="?page=product" data-page="product">
                        <i class="fa-solid fa-box-open"></i>
                        <span class="links-name">Sản phẩm</span>
                    </a>
                    <span class="tooltip">Sản phẩm</span>
                <?php endif; ?>
            </li>
            <li>
                <?php if (checkPermission('Nhà cung cấp')): ?>
                    <a href="?page=provider" data-page="provider">
                        <i class="fa-solid fa-truck-field"></i>
                        <span class="links-name">Nhà cung cấp</span>
                    </a>
                    <span class="tooltip">Nhà cung cấp</span>
                <?php endif; ?>
            </li>
            <li class="detail-order-effect">
                <?php if (checkPermission('Đơn hàng')): ?>
                    <a href="?page=order" data-page="order">
                        <i class="fa-solid fa-receipt"></i>
                        <span class="links-name">Đơn hàng</span>
                    </a>
                    <span class="tooltip">Đơn hàng</span>
                <?php endif; ?>
            </li>
            <li>
                <?php if (checkPermission('Phiếu nhập')): ?>
                    <a href="?page=phieunhap" data-page="phieunhap">
                        <i class="fa-solid fa-file-invoice"></i>
                        <span class="links-name">Phiếu nhập</span>
                    </a>
                    <span class="tooltip">Phiếu nhập</span>
                <?php endif; ?>
            </li>
            <li>
                <?php if (checkPermission('Khuyến mãi')): ?>
                    <a href="?page=voucher" data-page="voucher">
                        <i class="fa-solid fa-gift"></i>
                        <span class="links-name">Khuyến mãi</span>
                    </a>
                    <span class="tooltip">Khuyến mãi</span>
                <?php endif; ?>
            </li>
            <li>
                <?php if (checkPermission('Thống kê')): ?>
                    <a href="?page=statics" data-page="statics">
                        <i class="fa-solid fa-chart-line"></i>
                        <span class="links-name">Thống kê</span>
                    </a>
                    <span class="tooltip">Thống kê</span>
                <?php endif; ?>
            </li>
            <li>
                <?php if (checkPermission('Yêu cầu hoàn tiền')): ?>
                    <a href="?page=refund_requests" data-page="refund_requests">
                        <i class="fa-solid fa-money-bill-wave"></i>
                        <span class="links-name">Yêu cầu hoàn tiền</span>
                    </a>
                    <span class="tooltip">Yêu cầu hoàn tiền</span>
                <?php endif; ?>
            </li>
        </ul>
    </div>

    <a href="logout.php" class="log-out hidden-log-out">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span>Đăng xuất</span>
    </a>

    <div class="content">
        <?php
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            } else {
                $page = 'employeeinfo';
            }

            switch ($page) {
                case 'statics':
                    include(__DIR__ . "/thongke.php");
                    break;
                case 'product':
                    include(__DIR__ . "/Product/ProductView.php");
                    break;
                case 'order':
                    include(__DIR__ . "/order/order.php");
                    break;
                case 'orderdetail':
                    if (isset($_GET['idHD'])) {
                        include "order/orderdetail.php";
                    } else {
                        echo "<p style='color:red;'>Không tìm thấy đơn hàng</p>";
                    }
                    break;
                case 'provider':
                    include(__DIR__ . "/Provider/ProviderView.php");
                    break;
                case 'calendar':
                    include "calendar.php";
                    break;
                case 'voucher':
                    include "voucher/voucher.php";
                    break;
                case 'edit_voucher':
                    break;
                case 'add_voucher':
                    include "voucher/add_voucher.php";
                    break;
                case 'employeeinfo':
                    include(__DIR__ . "/Employee/EmployeeInfo.php");
                    break;
                case 'role':
                    include "role/role.php";
                    break;
                case 'account':
                    include "account/account.php";
                    break;
                case 'employee':
                    include "employee/employee.php";
                    break;
                case 'updateEmployee':
                    if (isset($_GET['idTK'])) {
                        include "employee/updateEmployee.php";
                    } else {
                        echo "<p style='color:red;'>Không tìm thấy ID nhân viên</p>";
                    }
                    break;
                case 'TakeLeave':
                    include "employee/TakeLeave.php";
                    break;
                case 'requestLeave':
                    include "employee/requestLeave.php";
                    break;
                case 'SalaryView':
                    include "Manager/SalaryView.php";
                    break;
                case 'add_account':
                    include "account/add_account.php";
                    break;
                case 'phieunhap':
                    include(__DIR__ . "/Receipt/ReceiptView.php");
                    break;
                case 'refund_requests':
                    include(__DIR__ . "/refund/refund_requests.php");
                    break;
                case 'add_employee':
                    include "employee/addEmployee.php";
                    break;
                default:
                    echo "<h2>Không tìm thấy trang!</h2>";
                    break;
            }
        ?>
    </div>
</body>

<script>
// document.addEventListener('DOMContentLoaded', function () {
//     function getRandomColor() {
//         var letters = '0123456789ABCDEF';
//         var color = '#';
//         for (var i = 0; i < 6; i++) {
//             color += letters[Math.floor(Math.random() * 16)];
//         }
//         return color;
//     }

//     setInterval(function () {
//         var contentElement = document.querySelector('.container');
//         if (contentElement) {
//             contentElement.style.boxShadow = "0px 8px 44px " + getRandomColor();
//         }
//     }, 200);
// });

// document.addEventListener('DOMContentLoaded', function () {
//     function getRandomColor() {
//         var letters = '0123456789ABCDEF';
//         var color = '#';
//         for (var i = 0; i < 6; i++) {
//             color += letters[Math.floor(Math.random() * 16)];
//         }
//         return color;
//     }

//     setInterval(function () {
//         var contentElement = document.querySelector('.container');
//         if (contentElement) {
//             // Tạo hiệu ứng box-shadow với nhiều màu sắc
//             contentElement.style.boxShadow = `
//                 0px 0px 10px ${getRandomColor()},
//                 0px 0px 20px ${getRandomColor()},
//                 0px 0px 30px ${getRandomColor()},
//                 0px 0px 40px ${getRandomColor()}
//             `;
//         }
//     }, 200); // Thay đổi box-shadow mỗi 200ms
// });
</script>

<script src="../../js/admin/index.js"></script>