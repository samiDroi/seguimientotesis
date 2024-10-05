@extends('layouts.base')
 @section('content')
 <h1>Guardar nueva unidad</h1>
 <form action="{{ route('unidades.update', $unidad->id_unidad) }}" method="POST">
    @csrf
    @method('PUT') <!-- Necesario para indicar que se trata de una actualización -->

    <div>
        <label for="nombre_unidad">Nombre de la Unidad:</label>
        <input type="text" id="nombre_unidad" name="nombre_unidad" value="{{ old('nombre_unidad', $unidad->nombre_unidad) }}" required>
    </div>

    <button type="submit">Actualizar Unidad</button>
    <button><a href="{{ route('unidades.index') }}">Cancelar</a></button>
</form>
 @endsection