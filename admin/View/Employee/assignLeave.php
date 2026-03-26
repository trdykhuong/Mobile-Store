<?php
    $conn = mysqli_connect("localhost", "root", "", "chdidong", 3306);
    if (!$conn) {
        die("Kết nối thất bại: " . mysqli_connect_error());
    }

    // Xử lý khi gửi form (lưu ý vì lúc load lại nó bị thêm nhầm vào id với ngày lễ đó)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tenNgayLe = mysqli_real_escape_string($conn, $_POST['tenNgayLe']);
        $ngay = mysqli_real_escape_string($conn, $_POST['ngay']);

        // Kiểm tra ngày nhập vào
        $today = date("Y-m-d"); // Lấy ngày hiện tại ở dạng YYYY-MM-DD
        if ($ngay <= $today) {
            $message = "Ngày nghỉ lễ phải lớn hơn ngày hôm nay.";
        } else {
            // Tiến hành lưu vào cơ sở dữ liệu nếu ngày hợp lệ
            $sql = "INSERT INTO ngayle (TENNGAYLE, NGAY) VALUES ('$tenNgayLe', '$ngay')";
            if (mysqli_query($conn, $sql)) {
                $message = "Thêm ngày nghỉ lễ thành công!";
            } else {
                $message = "Lỗi: " . mysqli_error($conn);
            }
        }
    }
?>

<link rel="stylesheet" href="../../css/admin/assignLeave.css">

<body>
    <h1>Thêm ngày lễ</h1>
    <div class="add-content" id="assignLeave">
        <form method="POST" onsubmit="return validateForm();">
            <div class="item-form">
                <strong><label>Tên ngày lễ:</label></strong>
                <input type="text" id="tenNgayLe" name="tenNgayLe" required>
            </div>
            <div class="item-form">
                <strong><label>Ngày nghỉ:</label></strong>
                <input type="date" id="ngayBatDau" name="ngay" required>
            </div>
            <div class="item-btn">
                <button type="button" class="back-btn" onclick="closeAddAssignLeave()">Trở lại</button>
                <button type="submit" class="sb-btn">Lưu</button>
            </div>
        </form>
    </div>
</body>

<script>
    function validateForm() {
        let tenNgayLe = document.getElementById("tenNgayLe").value.trim();
        let ngay = document.getElementById("ngay").value;

        if (tenNgayLe === "") {
            alert("Vui lòng nhập tên ngày lễ.");
            return false;
        }

        if (ngay === "") {
            alert("Vui lòng chọn ngày.");
            return false;
        }

        // Kiểm tra ngày phải lớn hơn hôm nay
        let today = new Date();
        today.setHours(0, 0, 0, 0); // bỏ giờ để so sánh chỉ theo ngày

        let selectedDate = new Date(ngay);
        if (selectedDate <= today) {
            alert("Đi làm rồi mà còn đòi nghỉ má");
            return false;
        }

        return true;
    }

    document.getElementById("assignLeave").addEventListener("submit", function(event) {
            // Gọi hàm kiểm tra khi người dùng gửi form
            if (!validateForm()) {
                // Nếu validate trả về false, ngừng gửi form
                event.preventDefault();
            } 
        });

    if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
    }
</script>

<?php mysqli_close($conn); ?>