  const ctx = document.getElementById('myChart');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio'],
      datasets: [{
        label: '# Ganancias por mes (BS)',
        data: [6000, 8000, 9000, 5000, 6500, 7500, 8500],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });


  const ctx2 = document.getElementById('myChart2');
  new Chart(ctx2, {
      type: 'pie',
      data: {
      labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio'],
      datasets: [{
          label: '# Ganancias por mes (BS)',
          data: [6000, 8000, 9000, 5000, 6500, 7500, 8500],
          borderWidth: 1
      }]
      },
      options: {
      scales: {
          y: {
          beginAtZero: true
          }
      }
      }
  });

  const ctx3 = document.getElementById('myChart3');
  new Chart(ctx3, {
      type: 'line',
      data: {
      labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio'],
      datasets: [{
          label: '# Ganancias por mes (BS)',
          data: [6000, 8000, 9000, 5000, 6500, 7500, 8500],
          borderWidth: 1
      }]
      },
      options: {
      scales: {
          y: {
          beginAtZero: true
          }
      }
      }
  });

 

 