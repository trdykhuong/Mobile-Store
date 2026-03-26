
function showError(message) {
    const errorDiv = document.getElementById("error-message");
    errorDiv.innerText = message;
    errorDiv.style.display = "block";

    setTimeout(() => {
        errorDiv.style.display = "none";
    }, 3000);
}

function showSuccess(message) {
    const successDiv = document.getElementById("success-message");
    successDiv.innerText = message;
    successDiv.style.display = "block";

    setTimeout(() => {
        successDiv.style.display = "none";
    }, 3000);
}

function checkLogin() {
    fetch('../../View/login-register/check_login.php')
        .then(response => response.json())
        .then(data => {
            if (data.logged_in) {
                window.location.href = "../../View/cart/order-info.php";
            } else {
                window.location.href = "../../View/login-register/dangnhap.php";
            }
        })
        .catch(error => console.error("Lỗi:", error));
}
// bấm dô mua ngay => chạy code này
function buyNow() {
    fetch('../../View/login-register/check_login.php')
        .then(response => response.json())
        .then(data => {
            if (!data.logged_in) {
                window.location.href = "../../View/login-register/dangnhap.php";
                return;
            }

            const chooseColor = (document.getElementById("selected-color")?.value || "").trim().toLowerCase();
            const chooseCapacity = (document.getElementById("selected-capacity")?.value || "").trim().toLowerCase();

            const hasColor = document.querySelectorAll(".color-btn").length > 0;
            const hasCapacity = document.querySelectorAll(".capacity-btn").length > 0;

            let matchedBtn = null;

            if (hasColor || hasCapacity) {
                matchedBtn = Array.from(document.querySelectorAll(".capacity-btn")).find(btn => {
                    const btnColor = (btn.getAttribute("data-color") || "").toLowerCase();
                    const btnCapacity = (btn.getAttribute("data-capacity") || "").toLowerCase();

                    return btnColor === chooseColor && btnCapacity === chooseCapacity;
                });

                if (!matchedBtn) {
                    showError("Vui lòng chọn đầy đủ thông tin sản phẩm.");
                    return;
                }
            }

            const idCTSP = matchedBtn ? matchedBtn.getAttribute("data-id") : document.getElementById("data-idCTSP").value;
            const tonKho = matchedBtn ? parseInt(matchedBtn.getAttribute("data-stock")) : parseInt(document.getElementById("data-stock").value);

            if (tonKho <= 0) {
                showError("Sản phẩm đã hết hàng.");
                return;
            }

            const form = document.getElementById("muaNgayForm");
            document.getElementById("selected-idCTSP").value = idCTSP;

            const formData = new FormData(form);

            fetch("../../Controller/cart/CartController.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            showSuccess(data.message || "Mua ngay thành công!");
                        }
                    } else {
                        showError(data.message || "Có lỗi khi mua sản phẩm.");
                    }
                })
                .catch(error => {
                    console.error("Lỗi khi gửi mua ngay:", error);
                    showError("Lỗi không xác định.");
                });
        })
        .catch(error => {
            console.error("Lỗi khi kiểm tra đăng nhập:", error);
            showError("Không thể kiểm tra đăng nhập.");
        });
}

// hàm thêm vô cart 
function addToCart() {
    const form = document.getElementById("add-to-cart-form");
    const chooseColor = (document.getElementById("selected-color")?.value || "").trim().toLowerCase();
    const chooseCapacity = (document.getElementById("selected-capacity")?.value || "").trim().toLowerCase();

    const hasColorOptions = document.querySelectorAll(".color-btn").length > 0;
    const hasCapacityOptions = document.querySelectorAll(".capacity-btn").length > 0;

    if (hasColorOptions && !chooseColor) {
        showError("Vui lòng chọn màu sắc!");
        return;
    }

    if (hasCapacityOptions && !chooseCapacity && hasColorOptions) {
        showError("Vui lòng chọn dung lượng!");
        return;
    }

    let matchedBtn = null;
    let idCT = null;
    let stock = 0;

    if (hasColorOptions || hasCapacityOptions) {
        matchedBtn = Array.from(document.querySelectorAll(".capacity-btn")).find(btn => {
            const btnColor = (btn.getAttribute("data-color") || "").toLowerCase();
            const btnCapacity = (btn.getAttribute("data-capacity") || "").toLowerCase();
            return btnColor === chooseColor && btnCapacity === chooseCapacity;
        });

        if (!matchedBtn) {
            showError("Không tìm thấy phiên bản sản phẩm phù hợp!");
            return;
        }

        idCT = matchedBtn.getAttribute("data-id");
        stock = parseInt(matchedBtn.getAttribute("data-stock"));
    } else {
        idCT = document.getElementById("data-idCTSP").value;
        stock = parseInt(document.getElementById("data-stock").value);
    }

    if (stock <= 0) {
        showError("Sản phẩm đã hết hàng!");
        return;
    }

    fetch('../../View/login-register/check_login.php')
        .then(res => res.json())
        .then(data => {
            if (!data.logged_in) {
                window.location.href = "../../View/login-register/dangnhap.php";
                return;
            }

            // let soluong = prompt("Vui lòng nhập số lượng:");
            // soluong = parseInt(soluong);

            // Hiển thị modal nhập số lượng
            const modal = document.getElementById("quantity-modal");
            const input = document.getElementById("quantity-input");
            const confirmButton = document.getElementById("confirm-quantity");
            const cancelButton = document.getElementById("cancel-quantity");
            
            input.value = "";

            modal.style.display = "block";

            confirmButton.onclick = function () {
                let soluong = parseInt(input.value);
                // giới hạn số lượng mua
                // if (soluong > 10) {
                //     showError(data.message || "Số lượng không hợp lệ!");
                //     return;
                // }

                if (isNaN(soluong) || soluong <= 0) {
                    showError(data.message || "Số lượng không hợp lệ!");
                    return;
                }

                if (soluong > stock) {
                    showError(data.message || `Chỉ còn ${stock} sản phẩm trong kho!`);
                    return;
                }

                modal.style.display = "none";

                const formData = new URLSearchParams();
                formData.append("add_to_cart", 1);
                formData.append("idSP", form.querySelector("input[name='idSP']").value);
                formData.append("idCTSP", idCT);
                formData.append("soluong", soluong);
                formData.append("mausac", chooseColor);
                formData.append("dungluong", chooseCapacity);

                console.log("gì đay:  " + formData);

                fetch("../../Controller/cart/CartController.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: formData.toString()
                })
                .then(res => res.json())
                .then(resData => {
                    if (resData.success) {
                        showSuccess(resData.message || "Thêm vào giỏ hàng thành công!");
                        const countEl = document.getElementById("cart-count");
                        if (countEl && resData.quantity !== undefined) {
                            countEl.innerText = resData.quantity;
                        }
                    } else {
                        alert(resData.message || "Thêm vào giỏ hàng thất bại!");
                    }
                })
                .catch(err => {
                    console.error("Lỗi khi thêm vào giỏ hàng:", err);
                    alert("Có lỗi xảy ra khi thêm vào giỏ hàng!");
                });
            };

            cancelButton.onclick = function () {
                input.value = "";
                modal.style.display = "none";
            };
        })
        .catch(err => {
            console.error("Lỗi kiểm tra đăng nhập:", err);
            alert("Không thể kiểm tra đăng nhập!");
        });
}
