// Banner-1
const slides = document.querySelectorAll(".slide"); 
const btnLeft = document.querySelector(".btn-left"); 
const btnRight = document.querySelector(".btn-right"); 
const dotContainer = document.querySelector(".dots");

let curSlide = 0; 

const goToSlide = function (slide) { 
    slides.forEach( 
        (s, i) => (s.style.transform = `translateX(${100 * (i - slide)}%)`)
    );
};

const nextSlide = function () { 
    if (curSlide === slides.length - 1) { 
        curSlide = 0; 
    } else {
        curSlide++; 
    }
    goToSlide(curSlide); 
    activateDot(curSlide); 
};

const prevSlide = function () { 
    if (curSlide == 0) {
        curSlide = slides.length - 1;
    } else {
        curSlide--;
    }
    goToSlide(curSlide);
    activateDot(curSlide);
};
// Nhấn quay lại slide
if (btnRight && btnRight) {
    btnRight.addEventListener("click", nextSlide);
    btnLeft.addEventListener("click", prevSlide);
}

// Nhấn mũi tên của bàn phím để chuyển qua lại slide
document.addEventListener("keydown", function (e) {
    if (e.key === "ArrowLeft") prevSlide();
    if (e.key === "ArrowRight") nextSlide();
});
    
// Dot
const createDots = function () { 
    slides.forEach(function (_, i) { 
        dotContainer.insertAdjacentHTML(
            "beforeend",
            `<button class="dots__dot" data-slide="${i}"></button>`
        );
    });
};
    
const activateDot = function (slide) { 
    document.querySelectorAll(".dots__dot")
        .forEach((dot) => dot.classList.remove("dots__dot--active")); 

    document
        .querySelector(`.dots__dot[data-slide="${slide}"]`)
        .classList.add("dots__dot--active");
};

const init = function () { 
    goToSlide(0); 
    createDots();
    activateDot(0);
};

init();

dotContainer.addEventListener("click", function (e) { 
    if (e.target.classList.contains("dots__dot")) {
        const { slide } = e.target.dataset;
        goToSlide(slide);
        activateDot(slide);
    }
});
const startAutoSilde = function () {
    setInterval(nextSlide, 3500);
};
startAutoSilde();
// Banner-1

// Banner-2
const slidesSmall = document.querySelectorAll(".small-slide");
const btnLeftSmall = document.querySelector(".small-btn-left");
const btnRightSmall = document.querySelector(".small-btn-right");
const dotContainerSmall = document.querySelector(".small-dots");

let curSlideSmall = 0;

const goToSlideSmall = function (slide) {
    slidesSmall.forEach(
        (s, i) => (s.style.transform = `translateX(${100 * (i - slide)}%)`)
    );
};

const nextSlideSmall = function () {
    if (curSlideSmall === slidesSmall.length - 1) {
        curSlideSmall = 0;
    } else {
        curSlideSmall++;
    }
    goToSlideSmall(curSlideSmall);
    activateDotSmall(curSlideSmall);
};

const prevSlideSmall = function () {
    if (curSlideSmall == 0) {
        curSlideSmall = slidesSmall.length - 1;
    } else {
        curSlideSmall--;
    }
    goToSlideSmall(curSlideSmall);
    activateDotSmall(curSlideSmall);
};

// Gán sự kiện cho nút bấm
if (btnRightSmall && btnLeftSmall) {
    btnRightSmall.addEventListener("click", nextSlideSmall);
    btnLeftSmall.addEventListener("click", prevSlideSmall);
}

// Chuyển slide bằng phím mũi tên
document.addEventListener("keydown", function (e) {
    if (e.key === "ArrowLeft") prevSlideSmall();
    if (e.key === "ArrowRight") nextSlideSmall();
});

// Tạo dots
const createDotsSmall = function () {
    slidesSmall.forEach(function (_, i) {
        dotContainerSmall.insertAdjacentHTML(
            "beforeend",
            `<button class="small-dots__dot" data-slide="${i}"></button>`
        );
    });
};

const activateDotSmall = function (slide) {
    document.querySelectorAll(".small-dots__dot")
        .forEach((dot) => dot.classList.remove("small-dots__dot--active"));

    document
        .querySelector(`.small-dots__dot[data-slide="${slide}"]`)
        .classList.add("small-dots__dot--active");
};

const initSliderSmall = function () {
    goToSlideSmall(0);
    createDotsSmall();
    activateDotSmall(0);
};

initSliderSmall();

// Bắt sự kiện click vào dots
dotContainerSmall.addEventListener("click", function (e) {
    if (e.target.classList.contains("small-dots__dot")) {
        const { slide } = e.target.dataset;
        goToSlideSmall(slide);
        activateDotSmall(slide);
    }
});

// Tự động chạy slide
const startAutoSlideSmall = function () {
    setInterval(nextSlideSmall, 3000);
};
startAutoSlideSmall();
// Banner-2

// Back to top
window.onscroll = function () {
    scrollFunction();
}

function scrollFunction() {
    if (
        document.body.scrollTop > 50 ||
        document.documentElement.scrollTop > 50
    ) {
        document.querySelector(".back-to-top").style.display = "block";
    } else {
        document.querySelector(".back-to-top").style.display = "none";
    }
}

document.querySelector(".back-to-top").onclick = function () {
    document.body.scrollTop = 0; 
    document.documentElement.scrollTop = 0;
}

// Hiển thị thông báo đăng nhập thành công
document.addEventListener("DOMContentLoaded", function() {
    const successMessage = document.getElementById("success-message");
    if (successMessage) {
        setTimeout(function() {
            successMessage.style.display = "none";
        }, 3000);
    }
});