@extends('layouts.admin')

@section('content')
  @vite(['resources/js/app.js'])

  <div style="max-width: 600px; margin: auto;">
    <canvas id="miGrafico"></canvas>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const canvas = document.getElementById('miGrafico');
      if (canvas) {
        new Chart(canvas.getContext('2d'), {
          type: 'pie',
          data: {
            labels: ['Ene', 'Feb', 'Mar'],
            datasets: [{
              label: 'Ventas',
              data: [10, 20, 30],
              backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
              'rgba(54, 162, 235, 0.7)',
              'rgba(80, 200, 300, 0.7)'
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