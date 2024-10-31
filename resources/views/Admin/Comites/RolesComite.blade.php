@extends('layouts.base')
@section('content')
<h1>Panel de roles</h1>
<p>En este panel podra definir los roles que usaran en los comites de su area, una vez 
    definidos al crear comites nuevos los roles ingresados aqui apareceran en una lista de roles permitidos para los usuarios del comite. 
</p>
<form action="{{ route('programas.create') }}" method="POST">
    @csrf
    <div id="roles-container">
        <div class="rol-item">
            <label for="nombre_programa">Nombre del Rol</label>
            <input type="text" name="nombre_rol[]" required>
        </div>
    </div>

    <button type="button" id="agregarRol">Agregar otro rol</button>

    <button type="submit">Definir Roles</button>
</form>
@endsection
@section('js')
<script>
    document.getElementById('agregarRol').addEventListener('click', function() {
        const container = document.getElementById('roles-container');
        const newPrograma = document.createElement('div');
        newPrograma.classList.add('rol-item');
        newPrograma.innerHTML = `
            <label for="nombre">Nombre del Rol</label>
            <input type="text" name="nombre_rol[]" required>
        `;
        container.appendChild(newPrograma);
    });
</script>
@endsection