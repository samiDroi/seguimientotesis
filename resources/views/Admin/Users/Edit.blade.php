@extends('layouts.form')
@section("form")
<form action="{{ route('users.update',$usuario->id_user) }}" method="POST"a>
    @csrf
    @method('PUT')
    <h1 class="text-center mt-5">Informacion personal</h1>
    <div class="container bg-body-secondary shadow-lg">
        <p class="fs-4 pt-3 fw-semibold text-center ">Seleccione los Tipos de Usuario:</p>
        {{-- @dd($tiposUsuario) --}}
        <div class="row mb-5 ">
        <div class="col-4 text-center">
            <input class="form-check-input" type="checkbox" id="coordinador" name="nombre_tipo[]" value="5" {{ in_array(5, $tiposUsuario) ? 'checked' : '' }}>
            <label class="fs-5"  for="coordinador">Coordinador</label>
        </div>
        <div class="col-4 text-center" >
            <input class="form-check-input" type="checkbox"  id="docente" name="nombre_tipo[]" value="4" {{ in_array(4, $tiposUsuario) ? 'checked' : '' }}>
            <label class="fs-5" for="docente">Docente</label>
        </div>

        <div class="col-4 text-center" >
            <input class="form-check-input" type="checkbox"  id="alumno" name="nombre_tipo[]" value="3" {{ in_array(3, $tiposUsuario) ? 'checked' : '' }}>
            <label class="fs-5" for="alumno">Alumno</label>
        </div>
        </div>
        <div class="px-5">
    <label for="correo_electronico">Correo Electronico</label>
    <input class="form-control" type="email" name="correo_electronico" required value="{{ $usuario->correo_electronico }}">
    </div>

    <div class="px-5 mt-3">
    <label  for="nombre">Nombre(s)</label>
    <input class="form-control  "  type="text" name="nombre" required value="{{ $usuario->nombre }}">
    </div>

    <div class="px-5 mt-3">
    <label for="apellidos">Apellidos</label>
    <input class="form-control" id='apellidos' type="text" name="apellidos" required value="{{ $usuario->apellidos }}">
    </div>

    <div class="px-5 mt-3">
    <label for="username">Clave de trabajador</label>
    <input class="form-control" type="text" name="username" required value="{{ $usuario->username }}"> 
    </div>

    
    <button class="mt-3 ms-5 mt-5 btn btn-primary mb-5 text-centers" type="submit">Actualizar</button>
    
</form>
</div>
@endsection