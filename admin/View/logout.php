<?php
session_start();
session_destroy();  // Xóa toàn bộ session
header("Location: loginAdmin.php");  // Chuyển hướng về trang login
exit;
?>
