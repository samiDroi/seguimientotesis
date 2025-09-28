
console.log(programas)
const labels = programas.map(p => p.nombre_programa);
const labels2 = estados.map(p=>p.estado)
const labels3 = directores.map(p=>p.nombre);

  const data = programas.map(p => p.total_alumnos);
  const data2 = estados.map(p => p.total);
  const data3 = directores.map(p=>p.total_tesis);
  
  
    
    document.addEventListener('DOMContentLoaded', () => {
      const canvas = document.getElementById('miGrafico');
      const canvas2 = document.getElementById('miGrafico2');
      const canvas3 = document.getElementById('miGrafico3');


      if (canvas) {
        new Chart(canvas.getContext('2d'), {
          type: 'pie',
          data: {
            labels: labels,
            datasets: [{
              label: labels3,
              data: data,
              backgroundColor: [
              '#2c3e50',
              '#3498db',
              '#c0392b',
              '#e67e22',
              '#8e44ad',
              ]
              
               
            }]
          },
          options: {
            responsive: true,
            // Nota: Pie charts no usan "scales", puedes quitar esto
          }
        });
      }

      if (canvas2) {
        new Chart(canvas2.getContext('2d'), {
          type: 'doughnut',
          data: {
            labels: labels2,
            datasets: [{
              label: 'Estados',
              data: data2 ,
              backgroundColor: [
              '#2c3e50',
              '#c0392b',
              '#3498db',
              '#e67e22',
              '#8e44ad',
              '#f1c40f',
              ]
              
               
            }]
          },
          options: {
            responsive: true,
            // Nota: Pie charts no usan "scales", puedes quitar esto
          }
        });
      }



      if (canvas3) {
        new Chart(canvas3.getContext('2d'), {
          type: 'bar',
          data: {
            labels: labels3,
            datasets: [{
              label: 'Estados',
              data: data3 ,
              backgroundColor: [
              '#2c3e50',
              '#c0392b',
              '#3498db',
              '#e67e22',
              '#8e44ad',
              '#f1c40f',
              ]
              
               
            }]
          },
          options: {
            responsive: true,
            // Nota: Pie charts no usan "scales", puedes quitar esto
          }
        });
      }
      
    });