document.addEventListener("DOMContentLoaded", function () {
  const ctx = document.getElementById('progressChart');

  if (ctx) {
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Selesai', 'Sedang Berlangsung'],
        datasets: [{
          data: [75, 25],
          backgroundColor: ['#4CAF50', '#FF9800'],
          borderWidth: 0
        }]
      },
      options: {
        cutout: '70%',
        responsive: true,
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });
  } else {
    console.error("Canvas #progressChart tidak ditemukan!");
  }
});
