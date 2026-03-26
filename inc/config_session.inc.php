<?php
// Cấu hình các tùy chọn session
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

// Cấu hình cookie session
session_set_cookie_params([
    'lifetime' => 18000000000000,  // Thời gian sống của cookie
    'domain' => 'localhost',  // Domain cho cookie
    'path' => '/',  // Path của cookie
    'secure' => true,  // Chỉ gửi cookie qua HTTPS
    'httponly' => true  // Chỉ có thể truy cập cookie qua HTTP, không qua JavaScript
]);

// Khởi tạo session (nên được gọi ở đầu)
session_start();  

// Kiểm tra nếu người dùng đã đăng nhập
if (isset($_SESSION["user_id"])) {
    if (!isset($_SESSION['last_regeneration'])) {
        regenerate_session_id_loggedin();
    } else {
        $interval = 60 * 30;  // Mỗi 30 phút
        if (time() - $_SESSION['last_regeneration'] >= $interval) {
            regenerate_session_id_loggedin();
        }
    }
} else {
    // Nếu người dùng chưa đăng nhập
    if (!isset($_SESSION['last_regeneration'])) {
        regenerate_session_id();
    } else {
        $interval = 60 * 30;  // Mỗi 30 phút
        if (time() - $_SESSION['last_regeneration'] >= $interval) {
            regenerate_session_id();
        }
    }
}

// Hàm đổi session ID khi chưa đăng nhập
function regenerate_session_id() {
    session_regenerate_id(true);  // Đổi session ID và hủy ID cũ
    $_SESSION['last_regeneration'] = time();  // Cập nhật thời gian đổi ID
}

// Hàm đổi session ID khi đã đăng nhập
function regenerate_session_id_loggedin() {
    session_regenerate_id(true);  // Đổi session ID và hủy ID cũ

    $userId = $_SESSION["user_id"];  // Lấy user_id từ session
    $newSessionId = session_create_id();  // Tạo ID session mới
    $sessionId = $newSessionId . "_" . $userId;  // Kết hợp ID mới và user_id
    session_id($sessionId);  // Đặt session ID mới

    $_SESSION['last_regeneration'] = time();  // Cập nhật thời gian đổi ID
}