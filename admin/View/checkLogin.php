<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: loginAdmin.php");
    exit();
}

// checkLogin.php (hoặc file bạn muốn chứa hàm kiểm tra quyền)
function checkPermission($permission) {
    if (isset($_SESSION['permissions']) && in_array($permission, $_SESSION['permissions'])) {
        return true;
    }
    return false;
}

?>
