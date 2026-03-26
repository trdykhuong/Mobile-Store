document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-approve').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (confirm('Bạn có chắc chắn muốn duyệt yêu cầu hoàn tiền này?')) {
                fetch('../../admin/View/refund/approve_refund.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Lỗi:', error));
            }
        });
    });

    document.querySelectorAll('.btn-reject').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (confirm('Bạn có chắc chắn muốn duyệt yêu cầu hoàn tiền này?')) {
                fetch('../../admin/View/refund/reject_refund.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Lỗi:', error));
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const rows = document.querySelectorAll(".refund-row");
    const modal = document.getElementById("order-detail-modal");
    const modalContent = document.getElementById("order-detail-content");

    rows.forEach(row => {
        row.addEventListener("click", function () {
            const orderId = this.getAttribute("data-id");

            fetch(`../View/refund/get_order_details.php?idHD=${orderId}`)
                .then(response => response.text())
                .then(data => {
                    modal.classList.add("open-modal");
                    modalContent.classList.add("open-order-detail-content");
                    modalContent.innerHTML = data;

                    const closeModal = document.getElementById("close-modal");
                    if (closeModal) {
                        closeModal.addEventListener("click", function () {
                            modal.classList.remove("open-modal");
                            modalContent.classList.remove("open-order-detail-content");
                        });
                    }
                })
                .catch(error => console.error("Lỗi khi tải chi tiết đơn hàng:", error));
        });
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.classList.remove("open-modal");
            modalContent.classList.remove("open-order-detail-content");
        }
    });
});