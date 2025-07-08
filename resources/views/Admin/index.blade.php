@extends('layouts.admin')

@section('content')
  @vite(['resources/js/app.js'])


  {{-- @dd($alumnosPrograma[1]) --}}

<div class="d-flex justify-content-center container">
  <div class="row">
      <div class="text-center mt-5 col-sm-12 col-md-4"  >
       <h2>Estados Tesis </h2>
        <canvas id="miGrafico2" width="400" height="400"></canvas>
      </div>

      <div class="text-center mt-5 col-sm-12  col-md-4" >
        <h2>Alumnos por programas</h2>
        <canvas id="miGrafico" width="400" height="400"></canvas>
      </div>

      <div class="text-center mt-5 col-sm-12  col-md-4" >
        <h2>Directores con mas tesis</h2>
        <canvas id="miGrafico3" width="400" height="400"></canvas>
      </div>
  </div>
</div>
  


  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



  <script>
    const directores =@json($tesisDocentes);
    const programas = @json($alumnosPrograma);
     const estados = @json($estadosTesis);
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
  </script>
@endsection