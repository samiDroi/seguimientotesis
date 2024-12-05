@extends('layouts.base')
 @section('content')
 <div class="text-center mt-5"><h1>Editar programa seleccionado</h1></div>
 <form action="{{ route('programas.update', $programa->id_programa) }}" method="POST">
    @csrf
    @method('PUT') <!-- Necesario para indicar que se trata de una actualización -->

    <div class="container bg-body-secondary text-center py-4 my-4 shadow-lg">
        <label class="fs-3 fw-semibold mb-3" for="nombre_unidad">Nombre del programa academico:</label>
       <div class="px-5"> <input class="form-control  mb-4"style="height:55px;" type="text" id="nombre_programa" name="nombre_programa" value="{{ old('nombre_programa', $programa->nombre_programa) }}" required></div>
    </div>

    <div class="text-center  mb-5">
    <button class="btn btn-primary" type="submit">Actualizar Programa</button>
    <button class="btn btn-danger"><a class="text-light text-decoration-none" href="{{ route('programas.index',$programa->id_unidad) }}">Cancelar</a></button>
    </div>
</form>
 @endsection