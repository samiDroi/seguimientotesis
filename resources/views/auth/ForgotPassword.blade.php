@extends('layouts.form')
@section('form')
<h1>Recuperacion de contraseña</h1>
<h3>ingrese su correo electronico</h3>
    <form action="{{ Route("forgotPassword") }}" method="POST">
        @csrf
        <label for="correo_electronico">Email</label>
        <input type="text" name="correo_electronico">
        <button type="submit">Aceptar</button>
    </form>
@endsection