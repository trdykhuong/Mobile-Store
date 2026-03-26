<!-- CSS chung -->
<link rel="stylesheet" href="../../css/admin/OneForAll.css">
<link rel="stylesheet" href="../../css/admin/thongke.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body>
    <div class="header">    
        <div class="first-header">
            <p>Thống kê bán hàng</p>
        </div>
        <div class="second-header">
            <div class="second-header-main">
                <button class="home">
                    <a href="?page=employeeinfo"> 
                        <i class="fa-solid fa-house home-outline"></i>
                    </a>
                </button>
                <div class="line"></div>
                <button onclick="showChart('revenueChart')">Doanh thu</button>
                <button onclick="showProducts()">Sản phẩm</button>
                <button onclick="showChart('totalOrdersChart')">Đơn hàng</button>
                <button onclick="showChart('orderStatusChart')">Tỷ lệ đơn hàng</button>
                <button onclick="showChart('revenueGrowthChart')">Tăng trưởng</button>
            </div>
        </div>
    </div>

    <!-- Nội dung chính -->
    <main class="main">
        <div class='container'>
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
                <canvas id="topProductsChart"></canvas>
                <canvas id="totalOrdersChart"></canvas>
                <canvas id="orderStatusChart"></canvas>
                <canvas id="revenueGrowthChart"></canvas>
            </div>

            <!-- Danh sách sản phẩm bán chạy -->
            <div id="topProductsList">
                <h3>Sản phẩm bán chạy</h3>
                <ul id="productList"></ul>
            </div>
        </div>
    </main>
</body>

<script>
    let charts = {}; 
    let topProductNames = []; 
    let topProductSales = [];

    document.addEventListener("DOMContentLoaded", function() {
        fetch('../Controller/thongke.php')
            .then(response => response.json())
            .then(data => {
                topProductNames = data.productNames; 
                topProductSales = data.productSales; 

                charts.revenueChart = new Chart(document.getElementById('revenueChart').getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: data.months,
                        datasets: [{
                            label: 'Doanh thu (VND)',
                            data: data.revenues,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            fill: true
                        }]
                    },
                    options: { 
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Biểu đồ doanh thu hàng tháng',
                            }
                        }
                    }
                });

                charts.topProductsChart = new Chart(document.getElementById('topProductsChart').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: data.productNames,
                        datasets: [{
                            label: 'Số lượng bán',
                            data: data.productSales,
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: { 
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        plugins: {
                            title: {
                                display: true,
                                text: 'Biểu đồ số lượng sản phẩm bán chạy',
                            }
                        }
                    }                
                });

                charts.totalOrdersChart = new Chart(document.getElementById('totalOrdersChart').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: data.months,
                        datasets: [{
                            label: 'Tổng số đơn hàng',
                            data: data.totalOrders,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Biểu đồ Tổng số đơn hàng theo tháng'
                            }
                        }
                    }
                });

                charts.orderStatusChart = new Chart(document.getElementById('orderStatusChart').getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: data.orderStatusLabels,
                        datasets: [{
                            data: data.orderStatusData,
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(255, 206, 86, 0.6)'
                            ]
                        }]
                    },
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: 'Biểu đồ Tỷ lệ trạng thái đơn hàng'
                            }
                        }
                    }
                });

                charts.revenueGrowthChart = new Chart(document.getElementById('revenueGrowthChart').getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: data.months,
                        datasets: [{
                            label: 'Tỷ lệ tăng trưởng (%)',
                            data: data.revenueGrowth,
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 2,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Biểu đồ Tăng trưởng doanh thu'
                            }
                        }
                    }
                });

                showChart('revenueChart');
            })
            .catch(error => console.error('Lỗi tải dữ liệu thống kê:', error));
    });

    function showChart(chartId) {
        document.querySelectorAll('canvas').forEach(canvas => {
            canvas.style.display = (canvas.id === chartId) ? 'block' : 'none';
        });

        document.getElementById('topProductsList').style.display = 'none'; 

        document.querySelectorAll('.second-header button').forEach(button => {
            if (button.getAttribute('onclick') === `showChart('${chartId}')`) {
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }
        });
    }

    function showProducts() {
        showChart('topProductsChart'); 

        const productList = document.getElementById('productList');
        productList.innerHTML = ''; 
        topProductNames.forEach((productName, index) => {
            const li = document.createElement('li');
            li.textContent = `${productName}: ${topProductSales[index]} sản phẩm`;
            productList.appendChild(li);
        });
        document.getElementById('topProductsList').style.display = 'block'; 

        document.querySelectorAll('.second-header-main button').forEach(button => {
            if (button.getAttribute('onclick') === 'showProducts()') {
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }
        });
    }
</script>