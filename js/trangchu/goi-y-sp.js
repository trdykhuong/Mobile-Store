function fetchSuggestions() {
    let keyword = document.getElementById("search-box").value.trim();
    let suggestionsBox = document.getElementById("suggestions-box");

    if (keyword.length < 2) {
        suggestionsBox.style.display = "none";
        return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open("GET", "../showproduct/goiysp.php?keyword=" + encodeURIComponent(keyword), true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let suggestions = JSON.parse(xhr.responseText);
            if (suggestions.length > 0) {
                let html = "";
                suggestions.forEach(product => {
                    html += `
                        <a href="/View/showproduct/productdetail.php?id=${product.idSP}" class="suggestion-item">
                            <img src="/images/products/${product.IMG}" alt="${product.TENSP}">
                            <div class="suggestion-info">
                                <span class="suggestion-name">${product.TENSP}</span>
                                <span class="suggestion-price">${new Intl.NumberFormat().format(product.GIA)}đ</span>
                            </div>
                        </a>`;
                });
                suggestionsBox.innerHTML = html;
                suggestionsBox.style.display = "block";
            } else {
                suggestionsBox.style.display = "none";
            }
        }
    };
    xhr.send();
}

// Ẩn gợi ý 
document.addEventListener("click", function (event) {
    let suggestionsBox = document.getElementById("suggestions-box");
    if (!document.getElementById("search-box").contains(event.target)) {
        suggestionsBox.style.display = "none";
    }
});
