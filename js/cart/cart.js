document.addEventListener("DOMContentLoaded", function() {
    const cartTable = document.querySelector("table");

    cartTable.addEventListener("click", function(event) {
        let target = event.target;
        if (target.classList.contains("btn-plus") || target.classList.contains("btn-minus")) {
            let row = target.closest("tr");
            let idCTSP = row.dataset.id;
            let input = row.querySelector(".soluong");
            let thanhtien = row.querySelector(".thanhtien");
            let gia = parseInt(row.querySelector(".price").dataset.gia);
            let tongtienEl = document.getElementById("tongtien");

            let soluong = parseInt(input.value);
            if (target.classList.contains("btn-plus")) {
            // if (target.classList.contains("btn-plus") && soluong + 1 < 11 ) {
                checkStock(idCTSP, soluong + 1)
                    .then(maxStock => {
                        if (soluong + 1 <= maxStock) {
                            updateQuantity(row, idCTSP, soluong + 1, gia, thanhtien, tongtienEl);
                        } else {
                            alert(`Số lượng trong kho không đủ! Chỉ còn ${maxStock} sản phẩm.`);
                        }
                    })
                    .catch(error => console.error("Lỗi khi kiểm tra tồn kho:", error));
            } else if (target.classList.contains("btn-minus") && soluong > 1) {
                updateQuantity(row, idCTSP, soluong - 1, gia, thanhtien, tongtienEl);
            }
            // else {
            //     alert(`Số lượng sai quy định.`)
            // }
        }
    });

    function updateQuantity(row, idCTSP, soluong, gia, thanhtien, tongtienEl) {
        let newThanhTien = soluong * gia;
        row.querySelector(".soluong").value = soluong;
        thanhtien.innerText = newThanhTien.toLocaleString("vi-VN") + "đ";
    
        updateCart(idCTSP, soluong, row); // truyền row vào để lấy idSP, idCT
    
        calculateTotal();
    }
    

    function updateCart(idCTSP, soluong, row) {
        const idSP = row.querySelector(".idSP").value;
        const idCT = row.querySelector(".idCTSP").value;
    
        fetch("../../Controller/cart/CartController.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({
                update: idCTSP,
                idSP: idSP,
                idCT: idCT,
                soluong: soluong
            })
            
        });
        console.log("Dữ liệu gửi đi:", {
            update: idCTSP,
            idSP: idSP,
            idCT: idCT,
            soluong: soluong
        });
    }
    

    function checkStock(idCTSP, requestedQty) {
        return fetch(`check_stock.php?idCTSP=${idCTSP}`)
            .then(response => response.json())
            .then(data => {
                console.log("Stock response:", data); // THÊM LOG NÀY
                if (data.success) {
                    return data.stock;
                } else {
                    throw new Error("Không thể kiểm tra tồn kho.");
                }
            })
            .catch(err => {
                console.error("Lỗi checkStock:", err);
            });
    }
    

    function calculateTotal() {
        let total = 0;
        let selectedCount = 0;

        document.querySelectorAll('.cart-product-select:checked').forEach(checkbox => {
            selectedCount++;
            let row = checkbox.closest("tr");
            let quantity = parseInt(row.querySelector(".soluong").value);
            let price = parseInt(row.querySelector(".price").dataset.gia);
            total += quantity * price;
        });

        document.getElementById("tongtien").innerText = total.toLocaleString("vi-VN") + "đ";
        document.getElementById("selectedCount").innerText = selectedCount;
    }

    document.querySelectorAll('.cart-product-select').forEach(checkbox => {
        checkbox.addEventListener('change', calculateTotal);
    });

    calculateTotal();

    function toggleClearCheck() {
        let checkboxes = document.querySelectorAll('.cart-product-select');
        let checkedCheckboxes = document.querySelectorAll('.cart-product-select:checked').length;
        let checkContent = document.querySelector('.clear-all');
        let selectAllCheck = document.getElementById('select-all-cart');

        if (checkedCheckboxes === checkboxes.length && checkboxes.length > 0) {
            checkContent.classList.add('active');
        } else {
            checkContent.classList.remove('active');
        }

        if (checkedCheckboxes === checkboxes.length && checkboxes.length > 0) {
            selectAllCheck.checked = true;
            selectAllCheck.indeterminate = false;
        } else if (checkedCheckboxes > 0) {
            selectAllCheck.checked = false;
            selectAllCheck.indeterminate = true;
        } else {
            selectAllCheck.checked = false;
            selectAllCheck.indeterminate = false;
        }

        calculateTotal();
    }

    document.getElementById('select-all-cart').addEventListener('change', function(event) {
        let checkboxes = document.querySelectorAll('.cart-product-select');
        checkboxes.forEach(checkbox => checkbox.checked = event.target.checked);
        toggleClearCheck();
    });

    document.querySelectorAll('.cart-product-select').forEach(checkbox => {
        checkbox.addEventListener('change', toggleClearCheck);
    });

    toggleClearCheck();
});

function showError(message) {
    let errorDiv = document.getElementById("error-message");
    errorDiv.innerText = message;
    errorDiv.style.display = "block";

    setTimeout(() => {
        errorDiv.style.display = "none";
    }, 3000);
}

// Kiểm tra có chọn sản phẩm nào không, nếu không sẽ vô hiệu hóa nút Đặt hàng
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all-cart');
    const productCheckboxes = document.querySelectorAll('.cart-product-select');
    const buyNowBtn = document.getElementById('buy-now-btn');
    const noProductSelectedMessage = document.getElementById('no-product-selected');

    function updateBuyNowButtonState() {
        const anyChecked = Array.from(productCheckboxes).some(checkbox => checkbox.checked);
        buyNowBtn.disabled = !anyChecked;
        noProductSelectedMessage.style.display = anyChecked ? 'none' : 'block';
    }

    selectAllCheckbox.addEventListener('change', function() {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        updateBuyNowButtonState();
    });

    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBuyNowButtonState);
    });

    updateBuyNowButtonState();
});