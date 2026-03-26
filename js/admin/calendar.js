let calendar;
const idNV = document.getElementById("idNV").value;

document.addEventListener("DOMContentLoaded", function () {
    // Kiểm tra nếu phần tử `#calendar` được hiển thị
    const calendarElement = document.getElementById("calendar");
    const buttons = document.querySelectorAll(".second-header-main button");

    if (calendarElement) {
        // Thêm lớp `active` vào nút "Xem lịch chấm công"
        buttons.forEach(button => {
            if (button.getAttribute("onclick") === "LoadTimekeeping()") {
                button.classList.add("active");
            } else {
                button.classList.remove("active");
            }
        });
    }
});

function Checkin() {
    fetch('../Controller/Employee/InsertTimekeeping.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: idNV, action: 'checkin'})
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        LoadTimekeeping();
    })
    .catch(error => {
        alert('Lỗi khi check in');
        console.error(error);
    });
}

function Checkout() {
    fetch('../Controller/Employee/InsertTimekeeping.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: idNV, action: 'checkout'})
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        LoadTimekeeping();
    })
    .catch(error => {
        alert('Lỗi khi check out');
        console.error(error);
    });
}

function LoadTimekeeping() {
    const calendarEl = document.getElementById('calendar');

    if (!calendar) {
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            events: 'get_events.php',
            eventClick: function(info) {
                alert('Sự kiện: ' + info.event.title + '\nNgày: ' + info.event.start.toLocaleDateString());
            }
        });
        calendar.render();
    } else {
        calendar.refetchEvents();
    }
}