<?php
session_start();
session_unset();
session_destroy();
setcookie(session_name(), '', time() - 3600, '/'); // Xóa session cookie

header("Location: ../../View/trangchu/trangchu.php"); // Chuyển hướng về trang đăng nhập
exit();