@extends('layouts.base')
@section('content')
<h1 class="text-center mt-4">Panel de roles</h1>
<div class="container">
    <div class="row bg-body-secondary fs-4 py-5 shadow-lg">
<p>En este panel podra definir los roles que usaran en los comites de su area, una vez 
    definidos al crear comites nuevos los roles ingresados aqui apareceran en una lista de roles permitidos para los usuarios del comite. 
</p>
</div>
<form action="{{ route('programas.create') }}" method="POST">
    @csrf
    <div id="roles-container">
        <div class="rol-item mt-3">
            <label for="nombre_programa">Nombre del Rol</label>
            <input class="form-control mb-4" type="text" name="nombre_rol[]" autocomplete="off" required>
        </div>
    </div>

    <button class="btn btn-primary" type="button" id="agregarRol">Agregar otro rol</button>

    <button class="btn btn-success" type="submit">Definir Roles</button>
</form>
</div>
@endsection
@section('js')
<script>
    document.getElementById('agregarRol').addEventListener('click', function() {
        const container = document.getElementById('roles-container');
        const newPrograma = document.createElement('div');
        newPrograma.classList.add('rol-item');
        newPrograma.innerHTML = `
            <label for="nombre">Nombre del Rol</label>
            <input class="form-control mb-4" type="text" name="nombre_rol[]" autocomplete="off" required>
        `;
        container.appendChild(newPrograma);
    });
</script>
@endsection