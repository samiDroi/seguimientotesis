@extends('layouts.base')
 @section('content')
 <h1>Editar programa seleccionado</h1>
 <form action="{{ route('programas.update', $programa->id_programa) }}" method="POST">
    @csrf
    @method('PUT') <!-- Necesario para indicar que se trata de una actualización -->

    <div>
        <label for="nombre_unidad">Nombre del programa academico:</label>
        <input type="text" id="nombre_programa" name="nombre_programa" value="{{ old('nombre_programa', $programa->nombre_programa) }}" required>
    </div>

    <button type="submit">Actualizar Programa</button>
    <button><a href="{{ route('programas.index',$programa->id_unidad) }}">Cancelar</a></button>
</form>
 @endsection