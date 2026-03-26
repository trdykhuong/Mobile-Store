document.addEventListener("DOMContentLoaded", function() { 
    const btnHome = document.querySelector('.btn-home');
    const btnHistory = document.querySelector('.btn-history');
    const btnFavorite = document.querySelector('.btn-favorite');

    const formHome = document.querySelector('.user-info-home');
    const formHistory = document.querySelector('.purchase-history');
    const formFavorite = document.querySelector('.favorite-products');

    const contentWrapper = document.querySelector('.user-info-content-right');
    const operationSpan = document.querySelector('.operation'); 

    // Hàm cập nhật chiều cao theo form active
    function updateContentHeight(activeForm) {
        const formHeight = activeForm.scrollHeight; // Lấy chiều cao thực tế của form
        contentWrapper.style.height = formHeight + 'px';
    }

    // Reset form trước khi chuyển đổi form khác
    function resetForms() {
        formHome.classList.remove('active');
        formHistory.classList.remove('active');
        formFavorite.classList.remove('active');
    }

    // Hàm xóa lớp 'hover-effect' 
    function removeAllHoverEffects() {
        document.querySelectorAll('.list-of-operations li').forEach(item => {
            item.classList.remove('hover-effect');
        });
    }

    // Hiển thị Trang Chủ mặc định
    formHome.classList.add('active');
    document.querySelector('li.btn-home').classList.add('hover-effect');

    operationSpan.textContent = 'Trang Chủ';
    updateContentHeight(formHome);

    // Hiển thị Trang Chủ khi nhấn nút
    btnHome.addEventListener('click', function () {
        resetForms();

        removeAllHoverEffects();
        document.querySelector('li.btn-home').classList.add('hover-effect');

        formHome.classList.add('active');

        operationSpan.textContent = 'Trang Chủ';
        updateContentHeight(formHome);
    });

    // Hiển thị Lịch Sử Mua Hàng khi nhấn nút
    btnHistory.addEventListener('click', function () {
        resetForms();

        removeAllHoverEffects();
        document.querySelector('li.btn-history').classList.add('hover-effect');

        formHistory.classList.add('active');

        operationSpan.textContent = 'Lịch Sử Mua Hàng';
        updateContentHeight(formHistory);
    });

    // Hiển thị sản phẩm yêu thích khi nhấn nút
    btnFavorite.addEventListener('click', function () {
        resetForms();

        removeAllHoverEffects();
        document.querySelector('li.btn-favorite').classList.add('hover-effect');

        formFavorite.classList.add('active');

        operationSpan.textContent = 'Sản Phẩm Yêu Thích';
        updateContentHeight(formFavorite);
    });
});