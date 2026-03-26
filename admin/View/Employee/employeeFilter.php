<?php 
// Kết nối cơ sở dữ liệu
// $conn = mysqli_connect("localhost", "root", "", "chdidong", 3306);
// if (!$conn) {
//     die("Kết nối thất bại: " . mysqli_connect_error());
// }

// Lấy danh sách quyền
$sql_roles = mysqli_query($conn, "SELECT * FROM quyen WHERE idQUYEN <> 1 AND idQUYEN <> 0 ORDER BY idQUYEN DESC");

// Lưu danh sách quyền vào mảng để sử dụng nhiều lần
$roles = [];
while ($row = mysqli_fetch_assoc($sql_roles)) {
    $roles[] = $row;
}

// Bắt đầu form lọc
echo '
<form method="POST" class="filter">
    <div class="first-filter">
        <input name="search-filter" placeholder="Nhập từ khóa tìm kiếm" value="'.htmlspecialchars($_POST['search-filter'] ?? '').'">
        <button type="submit" class="filter-form" name="filter"><i class="fa-solid fa-search"></i></button>
    </div>

    <div class="second-filter">
        <div class="second-filter-role">
            <select name="filter-vtri" class="filter-form">
                <option value="0">--Vị trí làm việc--</option>';
                foreach ($roles as $role) {     
                    $selected = (isset($_POST['filter-vtri']) && $_POST['filter-vtri'] == $role['idQUYEN']) ? 'selected' : '';
                    echo "<option value='{$role['idQUYEN']}' $selected>{$role['TENQUYEN']}</option>";
                }

echo '
            </select>
        </div>

        <div class="second-filter-date">
            <label for="ngaylam-from" class="filter-form"> Thời gian: </label>
            <input name="ngaylam-from" type="date" value="'.htmlspecialchars($_POST['ngaylam-from'] ?? '').'">

            <label for="ngaylam-to" class="filter-form">---</label>
            <input name="ngaylam-to" type="date" value="'.htmlspecialchars($_POST['ngaylam-to'] ?? '').'">
        </div>

        <div class="second-filter-static">
            <label for="orderby">Trạng thái: </label>
            <select name="orderby" class="filter-form">
                <option value="0" '.(isset($_POST['orderby']) && $_POST['orderby'] == 0 ? 'selected' : '').'>Mở Khóa</option>
                <option value="1" '.(isset($_POST['orderby']) && $_POST['orderby'] == 1 ? 'selected' : '').'>Khóa</option>
            </select>
        </div>
    </div>
</form>';

// Câu truy vấn lấy danh sách nhân viên
$sql = "SELECT tk.idTK, nv.IMG, nv.GIOITINH, nv.NGAYSINH, nv.DIACHI, nv.NGAYVAOLAM, nv.TINHTRANG, tk.SDT, tk.EMAIL, tk.HOTEN, tk.idQUYEN, q.TENQUYEN, q.LUONGCB, tk.TRANGTHAI
        FROM nhanvien nv
        JOIN taikhoan tk ON nv.idTK = tk.idTK
        JOIN quyen q ON tk.idQUYEN = q.idQUYEN 
        WHERE q.idQUYEN <> 1 AND q.idQUYEN <> 0";

$conditions = [];

// Xử lý bộ lọc
if (isset($_POST['filter'])) {
    $vtri = intval($_POST['filter-vtri'] ?? 0);
    $from = $_POST['ngaylam-from'] ?? "";
    $to = $_POST['ngaylam-to'] ?? "";
    $text_search = $_POST['search-filter'] ?? "";
    $orderby = intval($_POST['orderby'] ?? 0);

    // Tìm kiếm theo họ tên
    if (!empty($text_search)) {
        $text_search = mysqli_real_escape_string($conn, $text_search);
        $conditions[] = " (tk.HOTEN LIKE '%$text_search%' OR tk.SDT LIKE '%$text_search%' OR tk.EMAIL LIKE '%$text_search%')";
    }

    // Lọc theo vị trí làm việc
    if ($vtri > 0) {
        $conditions[] = " tk.idQUYEN = $vtri";
    }

    // Lọc theo ngày vào làm
    if (!empty($from) && !empty($to)) {
        if (strtotime($to) < strtotime($from)) {
            echo "<script>alert('Ngày kết thúc phải lớn hơn ngày bắt đầu')</script>";
        } else {
            $conditions[] = " (nv.NGAYVAOLAM BETWEEN '$from' AND '$to')";
        }
    } elseif (!empty($from)) {
        $conditions[] = " nv.NGAYVAOLAM >= '$from'";
    } elseif (!empty($to)) {
        $conditions[] = " nv.NGAYVAOLAM <= '$to'";
    }

    // Nối điều kiện vào câu truy vấn
    if (!empty($conditions)) {
        
        $sql .= " AND " . implode(" AND ", $conditions);
    }

    // Sắp xếp theo trạng thái
    $sql .= $orderby == 0 ? " ORDER BY tk.TRANGTHAI ASC" : " ORDER BY tk.TRANGTHAI DESC";
}

// Debug SQL
echo "<script>console.log(" . json_encode($sql) . ");</script>";

// Chạy truy vấn
$result = mysqli_query($conn, $sql);

?>