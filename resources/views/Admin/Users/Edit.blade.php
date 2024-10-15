@extends('layouts.form')
@section("form")
<form action="{{ route('users.update',$usuario->id_user) }}" method="POST"a>
    @csrf
    @method('PUT')
    <h1>Informacion personal</h1>
        <label for="nombre_tipo">Seleccione los Tipos de Usuario:</label>
        {{-- @dd($tiposUsuario) --}}
        <div>
            <input type="checkbox" id="coordinador" name="nombre_tipo[]" value="5" {{ in_array(5, $tiposUsuario) ? 'checked' : '' }}>
            <label  for="coordinador">Coordinador</label>
        </div>
        <div>
            <input type="checkbox"  id="docente" name="nombre_tipo[]" value="4" {{ in_array(4, $tiposUsuario) ? 'checked' : '' }}>
            <label for="docente">Docente</label>
        </div>

        <div>
            <input type="checkbox"  id="alumno" name="nombre_tipo[]" value="3" {{ in_array(3, $tiposUsuario) ? 'checked' : '' }}>
            <label  for="alumno">Alumno</label>
        </div>
    <label for="correo_electronico">Correo Electronico</label>
    <input type="email" name="correo_electronico" required value="{{ $usuario->correo_electronico }}">

    <label for="nombre">Nombre(s)</label>
    <input type="text" name="nombre" required value="{{ $usuario->nombre }}">

    <label for="apellidos">Apellidos</label>
    <input id='apellidos' type="text" name="apellidos" required value="{{ $usuario->apellidos }}">

    <label for="username">Clave de trabajador</label>
    <input type="text" name="username" required value="{{ $usuario->username }}"> 

    <button type="submit">Actualizar</button>
</form>
@endsection