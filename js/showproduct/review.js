document.addEventListener("DOMContentLoaded", function() {
    let button = document.getElementById("show-more-reviews");
    if (button) {
        button.addEventListener("click", function() {
            document.querySelectorAll(".hidden-review").forEach(function(item) {
                item.style.display = "block";
            });
            button.style.display = "none";
        });
    }
});