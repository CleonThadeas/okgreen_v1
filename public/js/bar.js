const ctx = document.getElementById('barChart').getContext('2d');

const labels = ["Kaleng", "Kertas", "Logam", "Plastik", "Botol Kaca", "Kayu"];
const data = [15, 30, 7, 40, 28, 15];

let barChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: labels,
    datasets: [{
      label: 'Jumlah Sampah',
      data: data,
      backgroundColor: [
        '#A7C7A3', '#8BBF99', '#5A8F6E', '#2F5D50', '#264E41', '#7D8F85'
      ]
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
      title: {
        display: false,
      }
    }
  }
});

// 🔥 Event listener filter
document.getElementById('filterSampah').addEventListener('change', function() {
  const selected = this.value;

  if (selected === "all") {
    barChart.data.labels = labels;
    barChart.data.datasets[0].data = data;
  } else {
    const index = labels.indexOf(selected);
    barChart.data.labels = [labels[index]];
    barChart.data.datasets[0].data = [data[index]];
  }
  barChart.update();
});
