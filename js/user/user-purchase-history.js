document.addEventListener("DOMContentLoaded", function () {
    const detailButtons = document.querySelectorAll(".btn-detail");
    const formOrders = document.querySelector('.purchase-history-table'); // Bảng lịch sử mua hàng
    const formContent = document.querySelector('.purchase-history-content'); // Nội dung bao ngoài
    const returnButtons = document.querySelectorAll(".btn-return");

    // Xử lý khi nhấn nút "Chi tiết"
    detailButtons.forEach((btn) => {
        btn.addEventListener("click", function () {
            const orderId = btn.getAttribute("data-id");
            const orderDetail = document.querySelector(`.order-detail[data-id="${orderId}"]`);

            if (orderDetail) {
                orderDetail.style.zIndex = orderId;
                formOrders.classList.add("active");
                orderDetail.classList.add("active");

                // Lấy chiều cao của order-detail
                const orderDetailHeight = orderDetail.scrollHeight;

                // Đặt chiều cao của purchase-history-content = order-detail
                formContent.style.height = orderDetailHeight + "px";

                // Đảm bảo order-detail hiển thị đầy đủ
                orderDetail.style.height = orderDetailHeight + "px";
            }
        });
    });

    // Xử lý khi nhấn nút "Quay lại"
    returnButtons.forEach((returnBtn) => {
        returnBtn.addEventListener("click", function () {
            const orderDetail = returnBtn.closest(".order-detail");

            formOrders.classList.remove("active");
            if (orderDetail) {
                orderDetail.classList.remove("active");
            }

            //Khôi phục chiều cao ban đầu cho formContent
            formContent.style.height = formOrders.scrollHeight + "px";

            if (orderDetail) {
                orderDetail.style.height = "0"; // Thu gọn lại khi quay về
            }
        });
    });
});

function openReviewModal(button) {
    let idCTSP = button.getAttribute("data-id");
    let imgSrc = button.getAttribute("data-img");
    let tenSP = button.getAttribute("data-name");

    document.getElementById("review-modal").style.display = "flex";
    document.getElementById("review-modal").querySelector("img").src = "../../images/products/"+imgSrc;
    document.getElementById("review-modal").querySelector("img").alt = tenSP;
    document.getElementById("review-modal").querySelector(".modal-title").textContent = "Bạn cảm thấy " + tenSP + " như thế nào?";

    document.getElementById("selected-product-id").value = idCTSP;
}

// Đóng modal khi click vào nút thoát
function closeReviewModal() {
    document.getElementById("review-modal").style.display = "none";
}

// Đóng modal khi click ra ngoài
window.onclick = function(event) {
    let modal = document.getElementById("review-modal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
};

const stars = document.querySelectorAll('.modal-star');
const label = document.getElementById('modal-rating-label');

// Mức đánh giá theo số sao
const ratings = ["Rất Tệ", "Tệ", "Bình Thường", "Tốt", "Rất Tốt"];
const colors = ["#FF6B6B", "#FF9F43", "#FFD93D", "#6BCB77", "#4D96FF"];

stars.forEach((star, index) => {
    star.addEventListener('click', () => {
        let rating = parseInt(star.getAttribute("data-value")); 

        // Reset tất cả sao
        stars.forEach(s => s.classList.remove('filled'));

        // Đánh dấu sao được chọn
        for (let i = 0; i <= index; i++) {
            stars[i].classList.add('filled');
            stars[i].style.color = colors[index]; // Đổi màu theo mức
        }

        // Hiển thị nhãn
        label.textContent = ratings[index];
        label.style.color = colors[index];

        // Lưu giá trị sao vào input ẩn
        document.getElementById("selected-rating").value = rating;
    });
});

function submitReview(event) {
    event.preventDefault(); // Ngăn chặn form bị submit mặc định

    let rating = document.getElementById("selected-rating").value;
    let comment = document.getElementById("review-comment").value;
    let idCTSP = document.getElementById("selected-product-id")?.value;

    if (!idCTSP) {
        alert("Lỗi: Không tìm thấy sản phẩm cần đánh giá!");
        return;
    }

    if (rating == 0) {
        alert("Vui lòng chọn số sao!");
        return;
    }

    fetch("../../Controller/rating/saveReview.php", {
        method: "POST",
        credentials: "include",
        body: JSON.stringify({
            rating,
            comment,
            idCTSP
        }),
        headers: {
            "Content-Type": "application/json"
        }
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === "success") {
            alert("Đánh giá đã được gửi!");
            window.location.reload();
        } else {
            alert("Lỗi khi gửi đánh giá: " + data);
        }
    })
    .catch(error => console.error("Lỗi:", error));
}

document.addEventListener("DOMContentLoaded", function () {
    // Xử lý khi nhấn nút đánh giá
    document.querySelector(".sumit-evaluation").addEventListener("click", submitReview);

    // Thiết lập overflow-y cho nội dung lịch sử mua hàng
    let purchaseHistory = document.querySelector(".purchase-history-content");
    let purchaseHistoryDetail = document.querySelector(".order-detail");

    if (purchaseHistory) {
        purchaseHistory.style.overflowY = "auto";
    }

    if (purchaseHistoryDetail) {
        purchaseHistoryDetail.style.overflowY = "auto";
    }
});

// Cập nhật trạng thái và lọc
document.addEventListener("DOMContentLoaded", function() {
    const receivedButtons = document.querySelectorAll(".btn-received");
    const cancelButtons = document.querySelectorAll(".btn-cancel-order");
    const statusFilter = document.getElementById("status-filter");
    const orderRows = document.querySelectorAll(".order-row");

    receivedButtons.forEach(button => {
        button.addEventListener("click", function() {
            const orderId = this.getAttribute("data-id");

            fetch("../../View/user/update-order-status.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `idHD=${orderId}&status=4`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Trạng thái đơn hàng đã được cập nhật.");
                    location.reload();
                } else {
                    alert("Có lỗi xảy ra. Vui lòng thử lại.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Có lỗi xảy ra. Vui lòng thử lại.");
            });
        });
    });

    cancelButtons.forEach(button => {
        button.addEventListener("click", function() {
            const orderId = this.getAttribute("data-id");

            if (confirm("Bạn đã chắc chắn chưa?")) {
                fetch("../../View/user/update-order-status.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `idHD=${orderId}&status=5`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Đã hủy đơn hàng.");
                        location.reload();
                    } else {
                        alert("Có lỗi xảy ra. Vui lòng thử lại.");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Có lỗi xảy ra. Vui lòng thử lại.");
                });
            }
        });
    });

    statusFilter.addEventListener("click", function(event) {
        const status = event.target.getAttribute("data-status");

        // Tô đỏ mục li được chọn
        const lis = statusFilter.querySelectorAll("li");
        lis.forEach(li => {
            li.classList.remove("active");
        });
        event.target.classList.add("active");

        // Lọc các đơn hàng theo trạng thái
        orderRows.forEach(row => {
            if (status === "all" || row.getAttribute("data-status") === status) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
});

// Xử lí hoàn tiền
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-refund').forEach(button => {
        button.addEventListener('click', function () {
            const idHD = this.getAttribute('data-id');
            const amount = parseFloat(
                this.closest('.order-detail')
                    .querySelector('.total-price-detail span')
                    .innerText.replace(/\D/g, '')
            );

            if (confirm('Bạn có chắc chắn muốn yêu cầu hoàn tiền cho đơn hàng này?')) {
                fetch('../../View/user/user-purchase-history.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `request_refund=1&idHD=${idHD}&amount=${amount}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    alert('Có lỗi xảy ra khi gửi yêu cầu hoàn tiền.');
                });
            }
        });
    });
});