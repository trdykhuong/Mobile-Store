const rows = document.querySelectorAll('#product-table tbody tr');
const rowsPerPage = 5;
let currentPage = 1;
const totalPages = Math.ceil(rows.length / rowsPerPage);

function displayRows(page) {
    rows.forEach((row, index) => {
        row.style.display = (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) ? '' : 'none';
    });

    document.getElementById('prev-btn').disabled = page === 1;
    document.getElementById('next-btn').disabled = page === totalPages;

    const pageButtons = document.querySelectorAll('#page-numbers button');
    pageButtons.forEach((btn, idx) => {
        btn.classList.toggle('active', idx + 1 === page);
    });
}

function setupPagination() {
    const pageNumbers = document.getElementById('page-numbers');
    pageNumbers.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        if (i === currentPage) btn.classList.add('active');
            btn.addEventListener('click', () => {
                currentPage = i;
                displayRows(currentPage);
            });
            pageNumbers.appendChild(btn);
    }
}

document.getElementById('prev-btn').addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        displayRows(currentPage);
    }
});

document.getElementById('next-btn').addEventListener('click', () => {
    if (currentPage < totalPages) {
        currentPage++;
        displayRows(currentPage);
    }
});

setupPagination();
displayRows(currentPage);

// Hàm xử lý khi chọn tất cả 
document.addEventListener("DOMContentLoaded", function() {
    const selectAllCheckbox = document.getElementById("select-all");
    const productCheckboxes = document.querySelectorAll(".product-checkbox");
    const removeFavoritesButton = document.getElementById("remove-favorites");

    // Xử lý chọn tất cả
    selectAllCheckbox.addEventListener("change", function() {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        toggleRightContent();
    });

    // Xử lý bỏ yêu thích các sản phẩm được chọn
    removeFavoritesButton.addEventListener("click", function() {
        const selectedProducts = [];
        productCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const productId = checkbox.closest(".product-row").getAttribute("data-id");
                selectedProducts.push(productId);
            }
        });

        if (selectedProducts.length > 0) {
            fetch("../../View/user/remove-favorite-products.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ productIds: selectedProducts })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification("Các sản phẩm đã được bỏ yêu thích.");
                    // Xóa các sản phẩm đã bỏ yêu thích khỏi DOM
                    selectedProducts.forEach(productId => {
                        const productRow = document.querySelector(`.product-row[data-id="${productId}"]`);
                        if (productRow) {
                            productRow.remove();
                        }
                    });
                    toggleRightContent(); // Cập nhật lại trạng thái của right-content
                } else {
                    alert("Có lỗi xảy ra. Vui lòng thử lại. " + (data.message || ""));
                }
            })
            .catch(error => {
                console.error("Error:", error);
                showNotification("Có lỗi xảy ra. Vui lòng thử lại. " + error.message, true);
            });
        } else {
            alert("Vui lòng chọn ít nhất một sản phẩm.");
        }
    });

    // Hàm kiểm tra checkbox và hiển thị right-content
    function toggleRightContent() {
        let checkboxesChecked = document.querySelectorAll('.product-checkbox:checked').length > 0;
        let rightContent = document.querySelector('.right-content');

        // Hiển thị hoặc ẩn right-content
        if (checkboxesChecked) {
            rightContent.classList.add('active');
        } else {
            rightContent.classList.remove('active');
        }

        // Xử lý trạng thái "Chọn tất cả"
        let totalCheckboxes = document.querySelectorAll('.product-checkbox').length;
        let checkedCheckboxes = document.querySelectorAll('.product-checkbox:checked').length;
        let selectAll = document.getElementById('select-all');

        if (checkedCheckboxes === totalCheckboxes && totalCheckboxes > 0) {
            selectAll.checked = true;
            selectAll.indeterminate = false;
        } else if (checkedCheckboxes > 0) {
            selectAll.checked = false;
            selectAll.indeterminate = true;
        } else {
            selectAll.checked = false;
            selectAll.indeterminate = false;
        }
    }

    // Sự kiện khi bấm vào "Chọn tất cả"
    document.getElementById('select-all').addEventListener('change', function(event) {
        let checkboxes = document.querySelectorAll('.product-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = event.target.checked);
        toggleRightContent();
    });

    // Sự kiện khi bấm vào từng checkbox sản phẩm
    document.querySelectorAll('.product-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', toggleRightContent);
    });

    // Kiểm tra trạng thái ban đầu khi load trang
    toggleRightContent();
});

function showNotification(message, isError = false) {
    const notification = document.getElementById("notification");
    notification.textContent = message;
    notification.style.backgroundColor = isError ? "#f44336" : "#4caf50"; // Đỏ cho lỗi, xanh cho thành công
    notification.style.display = "block";

    setTimeout(() => {
        notification.style.display = "none";
    }, 3000); // Ẩn thông báo sau 3 giây
}

document.addEventListener("DOMContentLoaded", function () {
    const notification = document.getElementById("notification");
    notification.style.display = "none";
});