const modalAdd = document.getElementById('modal-add');
const modalEdit = document.getElementById('modal-edit');
const formAddVoucher = document.getElementById('add-voucher');
const formEditVoucher = document.getElementById('edit-voucher');


document.addEventListener('DOMContentLoaded', function() {
    // Lấy thông báo nếu có
    var alert = document.querySelector('.alert');
    
    if (alert) {
        // Thêm hiệu ứng fade-in khi thông báo hiển thị
        alert.style.opacity = 1;
        // Ẩn thông báo sau 3 giây
        setTimeout(function() {
            alert.style.opacity = 0;
        }, 3000);
    }
});

// Thêm voucher
function openAddVoucher() {
    modalAdd.classList.add('open-modal');
    formAddVoucher.classList.add('open-add-voucher');
    document.querySelector(".hidden-log-out").classList.add("active");
}

function closeAddVoucher() {
    modalAdd.classList.remove('open-modal');
    formAddVoucher.classList.remove('open-add-voucher');
    document.querySelector(".hidden-log-out").classList.remove("active");
}

// Sửa voucher
function openEditVoucher(voucherId) {
    modalEdit.classList.add('open-modal');
    formEditVoucher.classList.add('open-edit-voucher');
    document.querySelector(".hidden-log-out").classList.add("active");

    fetch(`../../admin/View/voucher/edit_voucher.php?id=${voucherId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Không thể tải nội dung chỉnh sửa.');
            }
            return response.text();
        })
        .then(html => {
            console.log(html);
            formEditVoucher.innerHTML = html;
        })
        .catch(error => {
            console.error(error);
            formEditVoucher.innerHTML = '<p>Không thể tải nội dung chỉnh sửa.</p>';
        });
}

// Đóng modal
function closeEditVoucher() {
    modalEdit.classList.remove('open-modal');
    formEditVoucher.classList.remove('open-edit-voucher');
    document.querySelector(".hidden-log-out").classList.remove("active");
    formEditVoucher.innerHTML = '';
}