document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('myChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Selesai', 'Sedang Berlangsung'],
                datasets: [{
                    data: [75, 25], // data persentase
                    backgroundColor: ['#4CAF50', '#FF9800'], // hijau & oranye
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%', // bikin tengahnya bolong (donut)
                responsive: true,
                plugins: {
                    legend: {
                        display: false // legend default disembunyiin (karena udah ada custom legend)
                    }
                }
            }
        });
    } else {
        console.error("Canvas #myChart tidak ditemukan!");
    }
});
