document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const currentPage = urlParams.get("page");

    // Kiểm tra nếu đang ở trang `orderdetail`
    if (currentPage === "orderdetail") {
        // Tìm phần tử có class `detail-order-effect` trong `index.php`
        const detailOrderEffect = document.querySelector(".detail-order-effect");
        if (detailOrderEffect) {
            detailOrderEffect.classList.add("active"); 
        }
    }
});