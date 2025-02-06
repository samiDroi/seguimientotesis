@extends('layouts.form')
 @section('form')
 <h1 class="text-center mt-5">Guardar nueva unidad</h1>
 <form action="{{ route('unidades.update', $unidad->id_unidad) }}" method="POST">
    @csrf
    @method('PUT') <!-- Necesario para indicar que se trata de una actualización -->

<div class="container">
    <div class="row bg-body-secondary text-center shadow-lg">

    <div class="mb-4 ">
        <label class=" my-4 fs-3 fw-semibold" for="nombre_unidad">Nombre de la Unidad:</label>
        <div class="mx-5"><input type="text" class="form-control form-floating mb-4 py-3" id="nombre_unidad" name="nombre_unidad" value="{{ old('nombre_unidad', $unidad->nombre_unidad) }}" required></div>
    </div>

    
    <div class="text-center ">
    <button class="btn btn-primary mb-4" type="submit">Actualizar Unidad</button>
    <button class="btn btn-danger mb-4"><a class="text-light text-decoration-none" href="{{ route('unidades.index') }}">Cancelar</a></button>
    </div>
    </div>
   
    </div>

</form>
 @endsection