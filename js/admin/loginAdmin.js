document.getElementById("login-form").addEventListener("submit", function(event) {
    event.preventDefault();

    var username = document.getElementsByName("username")[0].value;
    var password = document.getElementsByName("password")[0].value;

    var formData = new URLSearchParams();
    formData.append("username", username);
    formData.append("password", password);

    fetch("loginAdmin.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: formData.toString(),
    })
    .then(response => response.json())
    .then(data => {
        var movingDiv = document.querySelector(".moving-div");
        var errorElement = document.querySelector(".moving-div .error");

        if (data.status > 0) {
            if(data.status == 2) {
                movingDiv.style.display = "none";
                window.location.href = "changPass.php"; 
                return
            }
            // Xóa thông báo lỗi nếu đăng nhập thành công
            movingDiv.style.display = "none";
            window.location.href = "index.php"; // Chuyển hướng sau khi đăng nhập thành công
        } else {
            // Hiển thị thông báo lỗi trong form
            errorElement.textContent = data.message;
            movingDiv.style.display = "block"; // Hiển thị form lỗi
        }
    })
    .catch(error => {
        console.error("Error:", error);
    });
});