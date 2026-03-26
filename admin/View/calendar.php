<?php
    include('../Controller/connectDB.php');
    $id = $_SESSION['idNV'];
?>

<title>Chấm Công FullCalendar</title>
<!-- CSS chung -->
<link rel="stylesheet" href="../../css/admin/OneForAll.css">
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

<body>
    <input type="hidden" id="idNV" value="<?php echo isset($_SESSION['idNV']) ? $_SESSION['idNV'] : ''; ?>">
    <div class="header">    
        <div class="first-header">
            <p>Chấm Công Nhân Viên</p>
        </div>
        <div class="second-header">
            <div class="second-header-main">
                <button class="home">
                    <a href="?page=employeeinfo"> 
                        <i class="fa-solid fa-house home-outline"></i>
                    </a>
                </button>
                <div class="line"></div>
                <button onclick="Checkin()">Check In</button>
                <button onclick="Checkout()">Check Out</button>
                <button onclick="LoadTimekeeping()">Xem lịch chấm công</button>
            </div>
        </div>
    </div>

    <!-- Nội dung chính -->
    <main class="main">
        <div class="container">
            <div id="calendar"></div>
        </div>
    </main>
</body>

<script src="../../js/admin/calendar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>