@extends('layouts.form')
@section('form')
<form action="{{ route('programas.create') }}" method="POST">
    @csrf   

    <h1 class="text-center mt-5">Crear nuevo programa academico</h1>

    <div class="container bg-body-secondary pb-5 pt-3 shadow-lg  " id="programas-container">
        <div class="programa-item text-center">
            <div class="fs-3 fw-semibold mb-1"><label for="nombre_programa">Nombre del Programa</label></div>
            <div class="mx-5"><input type="text" name="nombre_programa[]" class="form-control" autocomplete="off" required></div>
        </div>
    </div>
    
    <div class="text-end me-5 mt-3">
    <button class="btn btn-primary" type="button" id="agregarPrograma">Agregar otro programa</button>

    <button class="btn btn-success " type="submit">Crear Programas</button>
    </div>
</form>

@endsection
@section('js')
    <script>
        document.getElementById('agregarPrograma').addEventListener('click', function() {
            const container = document.getElementById('programas-container');
            const newPrograma = document.createElement('div');
            newPrograma.classList.add('programa-item');
            newPrograma.innerHTML = `
                <div class="programa-item text-center mt-5">
            <div class="fs-3 fw-semibold mb-1"><label for="nombre_programa">Nombre del Programa</label></div>
            <div class="mx-5"><input type="text" name="nombre_programa[]" class="form-control" autocomplete="off" required></div>
                </div>
            `;
            container.appendChild(newPrograma);
        });
    </script>
@endsection