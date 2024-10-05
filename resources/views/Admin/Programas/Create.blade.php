@extends('layouts.form')
@section('form')
<form action="{{ route('programas.create') }}" method="POST">
    @csrf

    <div id="programas-container">
        <div class="programa-item">
            <label for="nombre_programa">Nombre del Programa</label>
            <input type="text" name="nombre_programa[]" required>
        </div>
    </div>

    <button type="button" id="agregarPrograma">Agregar otro programa</button>

    <button type="submit">Crear Programas</button>
</form>

@endsection
@section('js')
    <script>
        document.getElementById('agregarPrograma').addEventListener('click', function() {
            const container = document.getElementById('programas-container');
            const newPrograma = document.createElement('div');
            newPrograma.classList.add('programa-item');
            newPrograma.innerHTML = `
                <label for="nombre">Nombre del Programa</label>
                <input type="text" name="nombre_programa[]" required>
            `;
            container.appendChild(newPrograma);
        });
    </script>
@endsection