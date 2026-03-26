<?php      
    // Kết nối cơ sở dữ liệu
    $conn = mysqli_connect("localhost", "root", "", "chdidong", 3306);
    if (!$conn) {
        die("Kết nối thất bại: " . mysqli_connect_error());
    }
    
    // Lấy danh sách ngày nghỉ
    $listNgayNghi = mysqli_query($conn, "SELECT * FROM ngayle");
    
    // Lưu danh sách quyền vào mảng để sử dụng nhiều lần
    $takeLeave = [];
    while ($row = mysqli_fetch_assoc($listNgayNghi)) {
        $takeLeave[] = $row;
    }

?>

<!-- CSS chung -->
<link rel="stylesheet" href="../../css/admin/OneForAll.css">
<link rel="stylesheet" href="../../css/admin/takeleave.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<body>
    <div class="header">    
        <div class="first-header">
            <p>Tất cả nghỉ lễ</p>
        </div>
        <div class="second-header">
            <div class="second-header-main">
                <button class="home">
                    <a href="?page=employeeinfo"> 
                        <i class="fa-solid fa-house home-outline"></i>
                    </a>
                </button>
                <div class="line"></div>
                <!-- <a href="../employee/assignLeave.php" class="btn btn-filter"> -->
                    <button onclick="openAddAssignLeave()">Thêm ngày lễ</button>
                <!-- </a> -->
            </div>
        </div>
    </div>

    <main class="main">
        <div class="container">
            <div class="filter">
                <input type="month" id="filter-month">
                <input type="text" id="search" placeholder="Tìm kiếm...">
                <button class="btn btn-search" onclick="filterTable()"><i class="fa-solid fa-search"></i></button>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ngày</th>
                            <th>Tên ngày lễ</th>
                            <!-- <th>Hành động</th> -->
                        </tr>
                    </thead>
                    <tbody id="leave-list">
                        <?php foreach ($takeLeave as $row):?>
                            <tr onclick="openEditForm(<?= $row['idNGAYLE'] ?>, '<?= $row['NGAY'] ?>', '<?= htmlspecialchars($row['TENNGAYLE'], ENT_QUOTES) ?>')">
                                <td><?= $row['idNGAYLE'] ?></td>
                                <td><?= htmlspecialchars($row['NGAY']) ?></td>
                                <td><?= htmlspecialchars($row['TENNGAYLE']) ?></td>
                                <!-- <td><i class="fa fa-eye" ></i></td> -->
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="return">
                <a href="?page=employee">
                    <button type="button" class="btn-return">Quay lại</button>
                </a>
            </div>
        </div>

        <!-- Form sửa ngày lễ -->
        <div id="editModal" class="editModal">
            <div class="editModal-content" id="editModal-content">
                <h1>Sửa ngày lễ</h1>
                <div class="edit-main-content">
                    <input type="hidden" id="edit-id">
                    <div class="item-content">
                        <strong><label for="edit-ten">Tên ngày lễ:</label></strong>
                        <input type="text" id="edit-ten">
                    </div>
                    <div class="item-btn">
                        <button onclick="closeModal()">Hủy</button>
                        <button onclick="saveEdit()" class="sb-btn">Lưu</button>
                    </div>
                </div>
            </div>

            <!-- assignLeave.css -->
            <div class="addModal-content" id="addModal-content">
                <?php include "../View/Employee/assignLeave.php"?>
            </div>
        </div>
    </main>
</body>

<script>
    document.addEventListener('click', (e)=> {
        if (document.getElementById('editModal').contains(e.target) 
            && !document.getElementById('editModal-content').contains(e.target) 
            && !document.getElementById('addModal-content').contains(e.target)) {
                document.getElementById('editModal').classList.remove("openeditModal");
                closeModal()
                closeAddAssignLeave()
        }
    })

    function filterTable() {
        let searchValue = document.getElementById('search').value.toLowerCase();
        let filterMonth = document.getElementById('filter-month').value; // dạng yyyy-mm
        let rows = document.querySelectorAll('#leave-list tr');

        rows.forEach(row => {
            let ngayText = row.children[1]?.textContent.trim(); // cột ngày
            let tenNgayLe = row.children[2]?.textContent.toLowerCase();

            // Format lại thành yyyy-mm để so sánh
            let rowDate = new Date(ngayText);
            let rowMonthStr = rowDate.toISOString().slice(0, 7); // yyyy-mm

            let matchSearch = tenNgayLe.includes(searchValue);
            let matchMonth = !filterMonth || rowMonthStr === filterMonth;

            row.style.display = (matchSearch && matchMonth) ? '' : 'none';
        });
    }

    function openEditForm(id, ngay, tenNgayLe) {
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-ten').value = tenNgayLe;
        document.getElementById('editModal').classList.add("openeditModal");
        document.getElementById('editModal-content').classList.add("open-editModal-content");
        document.querySelector(".hidden-log-out").classList.add("active");
    }

    function closeModal() {
        document.getElementById('editModal').classList.remove("openeditModal");
        document.getElementById('editModal-content').classList.remove("open-editModal-content");
        document.querySelector(".hidden-log-out").classList.remove("active");
    }

    function saveEdit() {
        const id = document.getElementById('edit-id').value;
        const ten = document.getElementById('edit-ten').value;

        if (!ten) {
            alert("Vui lòng nhập đầy đủ thông tin!");
            return;
        }

        // Gửi dữ liệu đi (AJAX)
        fetch('../Controller/employee/UpdateLeave.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: id,
                ten: ten
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                location.reload();
            }
        })
        .catch(err => console.error(err));
    }

    function openAddAssignLeave() {
        document.getElementById('editModal').classList.add("openeditModal");
        document.getElementById('addModal-content').classList.add("open-addModal-content");
        document.querySelector(".hidden-log-out").classList.add("active");
    }

    function closeAddAssignLeave() {
        document.getElementById('editModal').classList.remove("openeditModal");
        document.getElementById('addModal-content').classList.remove("open-addModal-content");
        document.querySelector(".hidden-log-out").classList.remove("active");
    }

    document.addEventListener("DOMContentLoaded", function () {
        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = urlParams.get("page");

        if (currentPage === "TakeLeave") {
            const detailOrderEffect = document.querySelector(".update-employee-effect");
            if (detailOrderEffect) {
                detailOrderEffect.classList.add("active"); 
            }
        }
    });
</script>