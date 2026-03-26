<?php
// Bật báo lỗi chi tiết
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Đặt múi giờ
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Đường dẫn file log
$logFile = __DIR__ . '/logs/timekeeping.log';

// Hàm ghi log
function writeLog($message) {
    global $logFile;
    $logMessage = "[" . date('Y-m-d H:i:s') . "] $message\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Ghi log khi script bắt đầu chạy
writeLog("🟢 Script bắt đầu chạy. Chế độ: " . (php_sapi_name() === 'cli' ? "CLI" : "API"));

// Kết nối database
$path = __DIR__ . '/../connectDB.php';
if (!file_exists($path)) {
    writeLog("❌ Không tìm thấy file kết nối database: $path");
    die(json_encode(['status' => false, 'message' => 'Không tìm thấy file kết nối database']));
}

include($path);
writeLog("🟢 Đã kết nối thành công với database.");

// Kiểm tra kết nối database
if ($conn->connect_error) {
    writeLog("❌ Lỗi kết nối database: " . $conn->connect_error);
    die(json_encode(['status' => false, 'message' => 'Lỗi kết nối database']));
}

// Hàm tính toán thời gian làm việc và tăng ca
function calculateOvertime($checkin, $checkout) {
    $checkinTime = strtotime($checkin);
    $checkoutTime = strtotime($checkout);

    // Tính tổng thời gian làm việc (tính bằng giờ)
    $totalHours = ($checkoutTime - $checkinTime) / 3600;

    // Quy định số giờ làm việc bình thường
    $normalHours = 8;

    // Tính số giờ tăng ca
    $overtime = max(0, $totalHours - $normalHours);

    return $overtime;
}

// Hàm tự động checkout cho các ngày trước đó
function autoCheckoutPreviousDays($conn) {
    global $logFile;

    // Lấy ngày hiện tại
    $today = date('Y-m-d');

    // Lấy danh sách các ngày trước đó mà nhân viên chưa checkout
    $sql = "SELECT DISTINCT NGAYLAM FROM bangchamcong WHERE CHECKOUT IS NULL AND NGAYLAM < ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        writeLog("❌ Lỗi khi chuẩn bị câu lệnh SQL: " . $conn->error);
        return;
    }

    $stmt->bind_param("s", $today);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ngayLam = $row['NGAYLAM'];
            writeLog("🟢 Kiểm tra ngày: $ngayLam");

            // Lấy danh sách nhân viên chưa checkout trong ngày đó
            $sqlNV = "SELECT idNV, CHECKIN FROM bangchamcong WHERE NGAYLAM = ? AND CHECKOUT IS NULL";
            $stmtNV = $conn->prepare($sqlNV);
            if (!$stmtNV) {
                writeLog("❌ Lỗi khi chuẩn bị câu lệnh SQL: " . $conn->error);
                continue;
            }

            $stmtNV->bind_param("s", $ngayLam);
            $stmtNV->execute();
            $resultNV = $stmtNV->get_result();

            if ($resultNV->num_rows > 0) {
                while ($rowNV = $resultNV->fetch_assoc()) {
                    $idNV = $rowNV['idNV'];
                    $checkin = $rowNV['CHECKIN'];
                    $auto_checkout_time = '23:59:59'; // Giờ tự động checkout cho các ngày trước đó

                    // Tính toán thời gian làm việc và tăng ca
                    $overtime = calculateOvertime($checkin, $auto_checkout_time);

                    // Cập nhật checkout và tăng ca cho nhân viên
                    $sqlUpdate = "UPDATE bangchamcong SET CHECKOUT = ?, AUTO_CHECKOUT = 1, TANGCA = ? WHERE idNV = ? AND NGAYLAM = ? AND CHECKOUT IS NULL";
                    $stmtUpdate = $conn->prepare($sqlUpdate);

                    if (!$stmtUpdate) {
                        writeLog("❌ Lỗi khi chuẩn bị câu lệnh SQL update: " . $conn->error);
                        continue;
                    }

                    $stmtUpdate->bind_param("ssis", $auto_checkout_time, $overtime, $idNV, $ngayLam);
                    if ($stmtUpdate->execute()) {
                        writeLog("🔴 Auto checkout: Nhân viên ID: $idNV vào ngày $ngayLam lúc $auto_checkout_time, Tăng ca: $overtime giờ");
                    } else {
                        writeLog("❌ Lỗi khi thực thi câu lệnh SQL update: " . $stmtUpdate->error);
                    }
                }
            } else {
                writeLog("🟢 Không có nhân viên nào cần auto checkout trong ngày $ngayLam.");
            }
        }
    } else {
        writeLog("🟢 Không có ngày nào cần auto checkout.");
    }
}

// Định nghĩa biến
$today = date('Y-m-d');
$auto_checkout_time = '08:46:20'; // Giờ tự động checkout
$checkin_time = "08:00:00";
$checkout_time = "19:00:00";

// Nếu chạy từ CLI (Task Scheduler)
if (php_sapi_name() === 'cli') {
    writeLog("🟢 Đang chạy từ CLI (Task Scheduler).");

    // Tạo file test để kiểm tra Task Scheduler có chạy không
    $testFile = __DIR__ . "/test_scheduler.txt";
    file_put_contents($testFile, "[" . date('Y-m-d H:i:s') . "] Task Scheduler đã chạy!\n", FILE_APPEND);
    writeLog("🟢 Đã tạo file test: $testFile");

    // Auto checkout cho các ngày trước đó
    autoCheckoutPreviousDays($conn);

    // Auto checkout cho ngày hiện tại
    $now = date('H:i:s');
    writeLog("⏳ Kiểm tra Auto Checkout - Giờ hiện tại: $now, Giờ quy định: $auto_checkout_time");

    // Chuyển đổi thời gian sang timestamp để so sánh
    $nowTimestamp = strtotime($now);
    $autoCheckoutTimestamp = strtotime($auto_checkout_time);

    if ($nowTimestamp >= $autoCheckoutTimestamp) {
        $sql = "SELECT idNV, CHECKIN FROM bangchamcong WHERE NGAYLAM = ? AND CHECKOUT IS NULL";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            writeLog("❌ Lỗi khi chuẩn bị câu lệnh SQL: " . $conn->error);
            die();
        }

        $stmt->bind_param("s", $today);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $idNV = $row['idNV'];
                $checkin = $row['CHECKIN'];

                // Tính toán thời gian làm việc và tăng ca
                $overtime = calculateOvertime($checkin, $now);

                $sqlUpdate = "UPDATE bangchamcong SET CHECKOUT = ?, AUTO_CHECKOUT = 1, TANGCA = ? WHERE idNV = ? AND NGAYLAM = ? AND CHECKOUT IS NULL";
                $stmtUpdate = $conn->prepare($sqlUpdate);

                if (!$stmtUpdate) {
                    writeLog("❌ Lỗi khi chuẩn bị câu lệnh SQL update: " . $conn->error);
                    continue;
                }

                $stmtUpdate->bind_param("ssis", $now, $overtime, $idNV, $today);
                if ($stmtUpdate->execute()) {
                    writeLog("🔴 Auto checkout: Nhân viên ID: $idNV lúc $now, Tăng ca: $overtime giờ");
                } else {
                    writeLog("❌ Lỗi khi thực thi câu lệnh SQL update: " . $stmtUpdate->error);
                }
            }
        } else {
            writeLog("🟢 Không có nhân viên nào cần auto checkout.");
        }
    } else {
        writeLog("🟢 Chưa đến giờ auto checkout.");
    }

    writeLog("✅ Kết thúc Auto Checkout");
} else {
    // API xử lý checkin/checkout
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';
    $idNV = intval($input['id'] ?? 0);
    $now = date('H:i:s');

    writeLog("🟢 Yêu cầu từ API - Hành động: $action, ID nhân viên: $idNV, Giờ: $now");

    if (!in_array($action, ['checkin', 'checkout']) || $idNV <= 0) {
        writeLog("❌ Dữ liệu không hợp lệ: action=$action, id=$idNV");
        echo json_encode(['status' => false, 'message' => 'Dữ liệu không hợp lệ']);
        exit;
    }

    try {
        if ($action === 'checkin') {
            // Kiểm tra số lần checkin trong ngày
            $sqlCount = "SELECT COUNT(*) as total FROM bangchamcong WHERE DATE(NGAYLAM) = ? AND idNV = ?";
            $stmtCount = $conn->prepare($sqlCount);
            if (!$stmtCount) {
                writeLog("❌ Lỗi khi chuẩn bị câu lệnh SQL đếm số lần checkin: " . $conn->error);
                echo json_encode(['status' => false, 'message' => 'Lỗi hệ thống']);
                exit;
            }
        
            $stmtCount->bind_param("si", $today, $idNV);
            $stmtCount->execute();
            $resultCount = $stmtCount->get_result();
            $rowCount = $resultCount->fetch_assoc();
            $totalCheckins = $rowCount['total'];
        
            // Quy định số lần checkin tối đa trong ngày là 2 lần
            $maxCheckins = 2;
        
            // Nếu số lần checkin vượt quá quy định, từ chối checkin
            if ($totalCheckins >= $maxCheckins) {
                writeLog("❌ Nhân viên ID: $idNV đã checkin $totalCheckins lần trong ngày (vượt quá giới hạn).");
                echo json_encode(['status' => false, 'message' => 'Bạn đã checkin quá số lần quy định trong ngày']);
                exit;
            }
        
            // Kiểm tra xem nhân viên đã checkin và chưa checkout
            $sqlCheck = "SELECT * FROM bangchamcong WHERE DATE(NGAYLAM) = ? AND idNV = ? AND CHECKOUT IS NULL";
            $stmtCheck = $conn->prepare($sqlCheck);
            if (!$stmtCheck) {
                writeLog("❌ Lỗi khi chuẩn bị câu lệnh SQL kiểm tra checkin: " . $conn->error);
                echo json_encode(['status' => false, 'message' => 'Lỗi hệ thống']);
                exit;
            }
        
            $stmtCheck->bind_param("si", $today, $idNV);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();
        
            if ($resultCheck->num_rows > 0) {
                writeLog("❌ Nhân viên ID: $idNV đã checkin trong ngày và chưa checkout.");
                echo json_encode(['status' => false, 'message' => 'Bạn đã checkin trong ngày và chưa checkout']);
                exit;
            }
        
            // Tính hệ số dựa trên thời gian checkin (17:00:00 là mốc)
            $currentHour = date('H:i:s');
            $heso = (strtotime($currentHour) >= strtotime('17:00:00')) ? 1.5 : 1;
        
            writeLog("🟢 Nhân viên ID: $idNV checkin lúc $currentHour → Hệ số: $heso (Lần thứ " . ($totalCheckins + 1) . ")");
        
            // Thực hiện insert checkin
            $sqlInsert = "INSERT INTO bangchamcong (idNV, NGAYLAM, CHECKIN, HESO) VALUES (?, ?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            if (!$stmtInsert) {
                writeLog("❌ Lỗi khi chuẩn bị câu lệnh SQL insert checkin: " . $conn->error);
                echo json_encode(['status' => false, 'message' => 'Lỗi hệ thống']);
                exit;
            }
        
            // Sử dụng bind_param với kiểu 'd' cho hệ số thập phân
            $stmtInsert->bind_param("issd", $idNV, $today, $currentHour, $heso);
            if ($stmtInsert->execute()) {
                writeLog("✅ Nhân viên ID: $idNV - Checkin thành công lúc $currentHour (Hệ số: $heso)");
                echo json_encode(['status' => true, 'message' => 'Checkin thành công', 'time' => $currentHour, 'heso' => $heso]);
            } else {
                writeLog("❌ Lỗi khi thực thi SQL checkin: " . $stmtInsert->error);
                echo json_encode(['status' => false, 'message' => 'Lỗi hệ thống']);
            }
        }elseif ($action === 'checkout') {
            // Kiểm tra xem nhân viên có checkin chưa
            $sqlCheck = "SELECT * FROM bangchamcong WHERE DATE(NGAYLAM) = ? AND idNV = ? AND CHECKOUT IS NULL ORDER BY CHECKIN DESC LIMIT 1";
            $stmtCheck = $conn->prepare($sqlCheck);
            if (!$stmtCheck) {
                writeLog("❌ Lỗi khi chuẩn bị câu lệnh SQL kiểm tra checkout: " . $conn->error);
                echo json_encode(['status' => false, 'message' => 'Lỗi hệ thống']);
                exit;
            }
        
            $stmtCheck->bind_param("si", $today, $idNV);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();
            $lastRecord = $resultCheck->fetch_assoc();
        
            if (!$lastRecord) {
                writeLog("❌ Không thể checkout: Nhân viên ID: $idNV không có checkin hoặc đã checkout.");
                echo json_encode(['status' => false, 'message' => 'Không thể checkout!']);
                exit;
            }
        
            // Tính số giờ tăng ca
            $overtime = (strtotime($now) > strtotime($checkout_time)) ? ((strtotime($now) - strtotime($checkout_time)) / 3600) : 0;
            
 
       
            // Ghi log trước khi update dữ liệu
            writeLog("🟢 Nhân viên ID: $idNV chuẩn bị checkout vào lúc $now, Tăng ca dự kiến: $overtime giờ");
        
            // Thực hiện update checkout
            $sqlUpdate = "UPDATE bangchamcong SET CHECKOUT = ?, TANGCA = ? WHERE idNV = ? AND NGAYLAM = ? AND CHECKIN = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            if (!$stmtUpdate) {
                writeLog("❌ Lỗi khi chuẩn bị câu lệnh SQL update checkout: " . $conn->error);
                echo json_encode(['status' => false, 'message' => 'Lỗi hệ thống']);
                exit;
            }
        
            $stmtUpdate->bind_param("ssiss", $now, $overtime, $idNV, $today, $lastRecord['CHECKIN']);
            if ($stmtUpdate->execute()) {
                writeLog("✅ Nhân viên ID: $idNV - Checkout thành công vào lúc $now, Tăng ca: $overtime giờ");
                echo json_encode(['status' => true, 'message' => 'Checkout thành công', 'time' => $now, 'overtime' => $overtime]);
            } else {
                writeLog("❌ Lỗi khi thực thi SQL checkout: " . $stmtUpdate->error);
                echo json_encode(['status' => false, 'message' => 'Lỗi hệ thống']);
            }
        }
    }catch (Exception $e) {
        writeLog("❌ Lỗi hệ thống: " . $e->getMessage());
        echo json_encode(['status' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
    }
}

$conn->close();
writeLog("🟢 Đã đóng kết nối database.");